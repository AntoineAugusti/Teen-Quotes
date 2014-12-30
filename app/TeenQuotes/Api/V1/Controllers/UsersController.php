<?php namespace TeenQuotes\Api\V1\Controllers;

use App, Auth, Config, DB, Input, Session, Str;
use Buonzz\GeoIP\Laravel4\Facades\GeoIP;
use Carbon\Carbon;
use Exception;
use stojg\crop\CropEntropy;
use TeenQuotes\Countries\Models\Country;
use TeenQuotes\Exceptions\ApiNotFoundException;
use TeenQuotes\Http\Facades\Response;
use TeenQuotes\Newsletters\Models\Newsletter;
use TeenQuotes\Settings\Models\Setting;
use TeenQuotes\Users\Models\User;
use TeenQuotes\Users\Validation\UserValidator;
use Thomaswelton\LaravelGravatar\Facades\Gravatar;

class UsersController extends APIGlobalController {

	/**
	 * @var TeenQuotes\Users\Validation\UserValidator
	 */
	private $userValidator;

	protected function bootstrap()
	{
		$this->userValidator = App::make('TeenQuotes\Users\Validation\UserValidator');
	}

	public function destroy()
	{
		$this->userRepo->destroy($this->retrieveUser());

		return Response::json([
			'status'  => 'user_deleted',
			'success' => 'The user has been deleted.'
		], 200);
	}

	public function getUsers()
	{
		$u = $this->retrieveUser();

		return $this->show($u->id);
	}

	public function store($doValidation = true)
	{
		$data = Input::only(['login', 'password', 'email']);

		if ($doValidation)
			$this->userValidator->validateSignup($data);

		// Store the new user
		$avatar = Session::get('avatar', null);

		// If the new user has got a Gravatar, set the avatar
		if (is_null($avatar) AND Gravatar::exists($data['email']))
			$avatar = Gravatar::src($data['email'], 150);

		$user = $this->userRepo->create($data['login'], $data['email'], $data['password'],
			$_SERVER['REMOTE_ADDR'], Carbon::now()->toDateTimeString(),
			self::detectCountry(), self::detectCity(), $avatar
		);

		// Send a welcome e-mail and subscribe the user to the
		// weekly newsletter thanks to its observer

		return Response::json($user, 201, [], JSON_NUMERIC_CHECK);
	}

	public function show($user_id)
	{
		$user = $this->userRepo->showByLoginOrId($user_id);

		// User not found
		if (empty($user) OR $user->count() == 0)
			return Response::json([
				'status' => 404,
				'error' => 'User not found.'
			], 404);

		$data = $user->toArray();
		foreach (User::$appendsFull as $key) {
			$method = Str::camel('get_'.$key);
			$data[$key] = $user->$method();
		}

		$user->registerViewUserProfile();

		return Response::json($data, 200, [], JSON_NUMERIC_CHECK);
	}

	public function getSearch($query)
	{
		$page = $this->getPage();
		$pagesize = Input::get('pagesize', Config::get('app.quotes.nbQuotesPerPage'));

		// Get users
		$content = $this->getUsersSearch($page, $pagesize, $query);

		// Handle no users found
		$totalUsers = 0;
		if (is_null($content) OR empty($content) OR $content->count() == 0)
			throw new ApiNotFoundException('users');

		$totalUsers = $this->userRepo->countByPartialLogin($query);

		$data = self::paginateContent($page, $pagesize, $totalUsers, $content, 'users');

		return Response::json($data, 200, [], JSON_NUMERIC_CHECK);
	}

	public function putProfile($doValidation = true)
	{
		$data = [
			'gender'    => Input::get('gender'),
			'birthdate' => Input::get('birthdate'),
			'country'   => Input::get('country'),
			'city'      => Input::get('city'),
			'about_me'  => Input::get('about_me'),
			'avatar'    => Input::file('avatar'),
		];

		if ($doValidation)
			$this->userValidator->validateUpdateProfile($data);

		// Everything went fine, update the user
		$user = $this->retrieveUser();

		$this->userRepo->updateProfile($user, $data['gender'], $data['country'],
			$data['city'], $data['about_me'], $data['birthdate'],
		$data['avatar']);

		// Move the avatar if required
		if ( ! is_null($data['avatar'])) {
			$this->cropAndMoveAvatar($user, $data['avatar']);
		}

		return Response::json([
			'status'  => 'profile_updated',
			'success' => 'The profile has been updated.'
		], 200);
	}

	private function cropAndMoveAvatar(User $user, $avatar)
	{
		$filename = $user->id.'.'.$avatar->getClientOriginalExtension();
		$filepath = Config::get('app.users.avatarPath').'/'.$filename;

		// Save to the final location
		Input::file('avatar')->move(Config::get('app.users.avatarPath'), $filename);

		// Crop the image and save it
		$center = new CropEntropy($filepath);
		$croppedImage = $center->resizeAndCrop(Config::get('app.users.avatarWidth'), Config::get('app.users.avatarHeight'));
		$croppedImage->writeimage($filepath);
	}

	public function putPassword()
	{
		$user = $this->retrieveUser();

		$data = Input::only(['password', 'password_confirmation']);

		$this->userValidator->validateUpdatePassword($data);

		// Update new password
		$this->userRepo->updatePassword($user, $data['password']);

		return Response::json([
			'status'  => 'password_updated',
			'success' => 'The new password has been set.',
		], 200, [], JSON_NUMERIC_CHECK);
	}

	public function putSettings($userInstance = null)
	{
		if (is_null($userInstance))
			$user = $this->retrieveUser();
		else
			$user = $userInstance;

		// We just want booleans
		$data = [
			'notification_comment_quote' => Input::has('notification_comment_quote') ? filter_var(Input::get('notification_comment_quote'), FILTER_VALIDATE_BOOLEAN) : false,
			'hide_profile'               => Input::has('hide_profile') ? filter_var(Input::get('hide_profile'), FILTER_VALIDATE_BOOLEAN) : false,
			'weekly_newsletter'          => Input::has('weekly_newsletter') ? filter_var(Input::get('weekly_newsletter'), FILTER_VALIDATE_BOOLEAN) : false,
			'daily_newsletter'           => Input::has('daily_newsletter') ? filter_var(Input::get('daily_newsletter'), FILTER_VALIDATE_BOOLEAN) : false,
			'colors'                     => Input::get('colors'),
		];

		$this->userRepo->updateSettings($user, $data['notification_comment_quote'], $data['hide_profile']);

		// Update daily / weekly newsletters
		foreach (Newsletter::getPossibleTypes() as $newsletterType)
		{
			// The user wants the newsletter
			if ($data[$newsletterType.'_newsletter']) {
				// He was NOT already subscribed, store this in storage
				if ( ! $user->isSubscribedToNewsletter($newsletterType))
					$this->newslettersManager->createForUserAndType($user, $newsletterType);

				// He was already subscribed, do nothing
			}
			// The user doesn't want the newsletter
			else {
				// He was subscribed, delete this from storage
				if ($user->isSubscribedToNewsletter($newsletterType))
					$this->newslettersManager->deleteForUserAndType($user, $newsletterType);

				// He was not subscribed, do nothing
			}
		}

		// Update colors for quotes
		if ( ! in_array($data['colors'], Config::get('app.users.colorsAvailableQuotesPublished')))
			return Response::json([
				'status' => 'wrong_color',
				'error'  => 'This color is not allowed.'
			], 400);

		$this->settingRepo->updateOrCreate($user, 'colorsQuotesPublished', $data['colors']);

		// Observer: clean setting cache

		return Response::json([
			'status'  => 'profile_updated',
			'success' => 'The profile has been updated.'
		], 200);
	}

	public function getUsersSearch($page, $pagesize, $query)
	{
		return $this->userRepo->searchByPartialLogin($query, $page, $pagesize);
	}

	/**
	 * Try to detect the country of the user, otherwise select the default country (the most common one)
	 * @return string The country
	 */
	public static function detectCountry()
	{
		// List of know countries
		$availableCountries = App::make('TeenQuotes\Countries\Repositories\CountryRepository')->listNameAndId();

		try {
			$countryDetected = GeoIP::getCountry();
		} catch (Exception $e) {
			$selectedCountry = Country::getDefaultCountry();
		}

		// If the detected country in the possible countries, we will select it
		if ( ! isset($selectedCountry) AND in_array($countryDetected, array_values($availableCountries)))
			$selectedCountry = array_search($countryDetected, $availableCountries);
		else
			$selectedCountry = Country::getDefaultCountry();

		return $selectedCountry;
	}

	/**
	 * Try to detect the city of the user thanks to its IP address
	 * @return string The city detected
	 */
	public static function detectCity()
	{
		try {
			$cityDetected = GeoIP::getCity();
			return $cityDetected;
		} catch (Exception $e) {
			$selectedCity = "";
			return $selectedCity;
		}
	}
}