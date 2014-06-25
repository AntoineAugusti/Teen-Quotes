<?php

class UsersController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('guest', array('only' => 'store'));
		$this->beforeFilter('auth', array('only' => array('edit', 'update', 'putPassword', 'putSettings')));
	}

	/**
	 * Displays the signup form
	 *
	 * @return Response
	 */
	public function getSignup()
	{
		$data = [
			'pageTitle'       => Lang::get('auth.signupPageTitle'),
			'pageDescription' => Lang::get('auth.signupPageDescription'),
		];

		JavaScript::put([
			'didYouMean'         => Lang::get('auth.didYouMean'),
			'mailAddressInvalid' => Lang::get('auth.mailAddressInvalid'),
			'mailAddressValid'   => Lang::get('auth.mailAddressValid'),
			'mailAddressUpdated' => Lang::get('auth.mailAddressUpdated'),
			'mailgunPubKey'      => Config::get('services.mailgun.pubkey')
    	]);

		return View::make('auth.signup', $data);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = [
			'login'    => Input::get('login'),
			'password' => Input::get('password'),
			'email'    => Input::get('email'),
		];

		$validator = Validator::make($data, User::$rulesSignup);

		// Check if the form validates with success.
		if ($validator->passes()) {

			// Store the user
			$user             = new User;
			$user->login      = $data['login'];
			$user->email      = $data['email'];
			$user->password   = Hash::make($data['password']);
			$user->ip         = $_SERVER['REMOTE_ADDR'];
			$user->last_visit = Carbon::now()->toDateTimeString();
			$user->save();

			// Log the user
			Auth::login($user);

			// Subscribe the user to the weekly newsletter
			Newsletter::createNewsletterForUser($user, 'weekly');

			// Send the welcome email
			Mail::send('emails.welcome', $data, function($m) use($data)
			{
				$m->to($data['email'], $data['login'])->subject(Lang::get('auth.subjectWelcomeEmail'));
			});

			return Redirect::intended('/')->with('success', Lang::get('auth.signupSuccessfull', array('login' => $data['login'])));
		}

		// Something went wrong.
		return Redirect::route('signup')->withErrors($validator)->withInput(Input::except('password'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param string $user_id The id or the login of the user
	 * @param string $type If it's not false, it could be 'favorites' or 'comments'
	 * @return Response
	 */
	public function show($user_id, $type = false)
	{
		// Page number for quotes
		$pageNumber = Input::get('page', 1);

		// Get the user
		$user = User::where('login', $user_id)->orWhere('id', $user_id)->first();

		if (is_null($user))
			throw new UserNotFoundException;

		// Throw an exception if the user has an hidden profile
		// We do not throw this exception if the user is currently
		// viewing its own hidden profile
		if ($user->isHiddenProfile() AND !(Auth::check() AND Auth::user()->login == $user->login))
			throw new HiddenProfileException;

		// If the user hasn't got favorites but has published quotes, redirect
		if (!$type AND !$user->hasPublishedQuotes() AND $user->hasFavoriteQuotes())
			return Redirect::route('users.show', [$user->login, 'favorites']);
		// If the user hasn't published quotes but has favorites quotes, redirect
		elseif ($type == 'favorites' AND !$user->hasFavoriteQuotes() AND $user->hasPublishedQuotes())
			return Redirect::route('users.show', $user->login);


		// Build the data array
		// Keys: quotes, paginator, colors, type
		if ($type == 'favorites')
			$data = self::dataShowFavoriteQuotes($user, $pageNumber);
		elseif ($type == 'comments')
			$data = self::dataShowComments($user, $pageNumber);
		else
			$data = self::dataShowPublishedQuotes($user, $pageNumber);

		$data['user']                 = $user;
		$data['pageTitle']            = Lang::get('users.profilePageTitle', array('login' => $user->login));
		$data['pageDescription']      = Lang::get('users.profilePageDescription', array('login' => $user->login));
		$data['hideAuthorQuote']      = ($data['type'] == 'published');
		$data['commentsCount']        = $user->getTotalComments();
		$data['addedFavCount']        = $user->getAddedFavCount();
		$data['quotesPublishedCount'] = $user->getPublishedQuotesCount();
		$data['favCount']             = $user->getFavoriteCount();

		return View::make('users.show', $data);
	}

	private static function dataShowFavoriteQuotes(User $user, $pageNumber)
	{
		// Time to store quotes in cache
		$expiresAt = Carbon::now()->addMinutes(10);

		// Get the list of favorite quotes
		$arrayIDFavoritesQuotesForUser = $user->arrayIDFavoritesQuotes();

		// Fetch the quotes
		$quotes = Cache::remember(User::$cacheNameForFavorited.$user->id.'_'.$pageNumber, $expiresAt, function() use ($user, $arrayIDFavoritesQuotesForUser)
		{
			return Quote::whereIn('id', $arrayIDFavoritesQuotesForUser)
				->with('user')
				->orderBy(DB::raw("FIELD(id, ".implode(',', $arrayIDFavoritesQuotesForUser).")"))
				->paginate(Config::get('app.users.nbQuotesPerPage'))
				->getItems();
		});

		// Build the associated paginator
		$paginator = Paginator::make($quotes, count($arrayIDFavoritesQuotesForUser), Config::get('app.users.nbQuotesPerPage'));
		// FIXME: could be prettier
		$paginator->setBaseUrl('/users/'.$user->login.'/favorites');

		// Build the associative array  #quote->id => "color"
		$IDsQuotes = array();
		foreach ($quotes as $quote)
			$IDsQuotes[] = $quote->id;
		
		// Store it in session
		$colors = Quote::storeQuotesColors($IDsQuotes);

		// Fix the type of quotes we will display
		$type = 'favorites';

		return [
			'quotes'    => $quotes,
			'paginator' => $paginator,
			'colors'    => $colors,
			'type'      => $type,
		];
	}

	private static function dataShowComments(User $user, $pageNumber)
	{
		$comments = $user
			->comments()
			->with('user', 'quote')
			->orderDescending()
			->paginate(Config::get('app.users.nbQuotesPerPage'))
			->getItems();

		// Build the associated paginator
		$paginator = Paginator::make($comments, $user->getTotalComments(), Config::get('app.users.nbQuotesPerPage'));
		// FIXME: could be prettier
		$paginator->setBaseUrl('/users/'.$user->login.'/comments');

		// Fix the type of quotes we will display
		$type = 'comments';

		return [
			'quotes'    => $comments,
			'paginator' => $paginator,
			'type'      => $type,
		];
	}

	private static function dataShowPublishedQuotes(User $user, $pageNumber)
	{
		// Time to store quotes in cache
		$expiresAt = Carbon::now()->addMinutes(10);

		// Fetch the quotes
		$quotes = Cache::remember(User::$cacheNameForPublished.$user->id.'_'.$pageNumber, $expiresAt, function() use ($user)
		{
			return Quote::forUser($user)
				->published()
				->orderDescending()
				->paginate(Config::get('app.users.nbQuotesPerPage'))
				->getItems();
		});

		$numberQuotesPublishedForUser = Cache::remember(User::$cacheNameForNumberQuotesPublished.$user->id, $expiresAt, function() use ($user)
		{
			return Quote::forUser($user)
				->published()
				->count();
		});

		// Build the associated paginator
		$paginator = Paginator::make($quotes, $numberQuotesPublishedForUser, Config::get('app.users.nbQuotesPerPage'));

		// Colors that will be used for quotes
		// Build the associative array  #quote->id => "color"
		$IDsQuotes = array();
		foreach ($quotes as $quote)
			$IDsQuotes[] = $quote->id;
		
		// Store it in session
		$colors = Quote::storeQuotesColors($IDsQuotes, $user->getColorsQuotesPublished());

		// Fix the type of quotes we will display
		$type = 'published';

		return [
			'quotes'    => $quotes,
			'paginator' => $paginator,
			'colors'    => $colors,
			'type'      => $type,
		];
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string $id The login or the ID of the user
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::whereLogin($id)->orWhere('id', $id)->first();

		if ($user->login != Auth::user()->login)
			App::abort(401, 'Refused');
		else {

			// The color for published quotes
			$confColor = Setting::
					where('user_id', '=', $user->id)
					->where('key', '=', 'colorsQuotesPublished')
					->first();

			// Set the default color
			if (is_null($confColor))
				$selectedColor = Config::get('app.users.defaultColorQuotesPublished');
			else
				$selectedColor = $confColor->value;

			// Create an array like
			// ['blue' => 'Blue', 'red' => 'Red']
			$colorsInConf = array_keys(Config::get('app.users.colorsQuotesPublished'));
			$colorsAvailable = array_combine($colorsInConf, array_map('ucfirst', $colorsInConf));

			$data = [
				'gender'           => $user->gender,
				'listCountries'    => Country::lists('name', 'id'),
				// USA by default if the country is not set
				'selectedCountry'  => is_null($user->country) ? Country::$idUSA : $user->country,
				'user'             => $user,
				'selectedColor'    => $selectedColor,
				'colorsAvailable'  => $colorsAvailable,
				'pageTitle'        => Lang::get('users.editPageTitle'),
				'pageDescription'  => Lang::get('users.editPageDescription'),
				'weeklyNewsletter' => $user->isSubscribedToNewsletter('weekly'),
				'dailyNewsletter'  => $user->isSubscribedToNewsletter('daily'),
			];

			return View::make('users.edit', $data);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  string $id The login or the ID of the user
	 * @return Response
	 */
	public function update($id)
	{
		$data = [
			'gender'    => Input::get('gender'),
			'birthdate' => Input::get('birthdate'),
			'country'   => Input::get('country'),
			'city'      => Input::get('city'),
			'about_me'  => Input::get('about_me'),
			'avatar'    => Input::file('avatar'),
		];

		$validator = Validator::make($data, User::$rulesUpdate);

		if ($validator->passes()) {
			$user = Auth::user();
			$user->gender    = $data['gender'];
			$user->birthdate = $data['birthdate'];
			$user->country   = $data['country'];
			$user->city      = $data['city'];
			$user->about_me  = $data['about_me'];

			// Move the avatar
			if (!is_null($data['avatar'])) {
				$filename = $user->id.'.'.$data['avatar']->getClientOriginalExtension();

				Input::file('avatar')->move(Config::get('app.users.avatarPath'), $filename);

				$user->avatar = $filename;
			}

			$user->save();

			return Redirect::back()->with('success', Lang::get('users.updateProfileSuccessfull', array('login' => $user->login)));
		}

		// Something went wrong.
		return Redirect::back()->withErrors($validator)->withInput(Input::except('avatar'));
	}

	/**
	 * Update the password in storage
	 *
	 * @param  string $id The login or the ID of the user
	 * @return Response
	 */
	public function putPassword($id)
	{
		$data = [
			'password'              => Input::get('password'),
			'password_confirmation' => Input::get('password_confirmation'),
		];

		$validator = Validator::make($data, User::$rulesUpdatePassword);

		if ($validator->passes()) {
			$user = User::whereLogin($id)->orWhere('id', $id)->first();
			if ($user->login != Auth::user()->login)
				App::abort(401, 'Refused');
			$user->password = Hash::make($data['password']);
			$user->save();

			return Redirect::back()->with('success', Lang::get('users.updatePasswordSuccessfull', array('login' => $user->login)));
		}

		// Something went wrong.
		return Redirect::back()->withErrors($validator)->withInput(Input::all());
	}

	/**
	 * Update settings for the user
	 *
	 * @param  string $id The login or the ID of the user
	 * @return Response
	 */
	public function putSettings($id)
	{
		// We just want booleans
		$data = [
			'notification_comment_quote' => Input::has('notification_comment_quote') ? filter_var(Input::get('notification_comment_quote'), FILTER_VALIDATE_BOOLEAN) : false,
			'hide_profile'               => Input::has('hide_profile') ? filter_var(Input::get('hide_profile'), FILTER_VALIDATE_BOOLEAN) : false,
			'weekly_newsletter'          => Input::has('weekly_newsletter') ? filter_var(Input::get('weekly_newsletter'), FILTER_VALIDATE_BOOLEAN) : false,
			'daily_newsletter'          => Input::has('daily_newsletter') ? filter_var(Input::get('daily_newsletter'), FILTER_VALIDATE_BOOLEAN) : false,
			'colors' => Input::get('colors'),
		];

		// Change values for the users table
		$user = User::whereLogin($id)->orWhere('id', $id)->first();
		if ($user->login != Auth::user()->login)
			App::abort(401, 'Refused');
		$user->notification_comment_quote = $data['notification_comment_quote'];
		$user->hide_profile               = $data['hide_profile'];
		$user->save();

		// Update daily / weekly newsletters
		foreach (['daily', 'weekly'] as $newsletterType)
		{
			// The user wants the newsletter
			if ($data[$newsletterType.'_newsletter']) {
				// He was NOT already subscribed, store this in storage
				if (!$user->isSubscribedToNewsletter($newsletterType))
					Newsletter::createNewsletterForUser($user, $newsletterType);

				// He was already subscribed, do nothing
			}
			// The user doesn't want the newsletter
			else {
				// He was subscribed, delete this from storage
				if ($user->isSubscribedToNewsletter($newsletterType))
					Newsletter::forUser($user)->type($newsletterType)->delete();

				// He was not subscribed, do nothing
			}
		}

		// Update colors for quotes
		if (!in_array($data['colors'], array_keys(Config::get('app.users.colorsQuotesPublished'))))
			return Redirect::back()->with('warning', Lang::get('users.colorNotAllowed'));
		else {

			// Forget value in cache
			Cache::forget(User::$cacheNameForColorsQuotesPublished.$user->id);

			// Retrieve setting by the attributes
			// or instantiate a new instance
			$colorSetting = Setting::firstOrNew(
				[
					'user_id' => $user->id,
					'key' => 'colorsQuotesPublished'
				]);
			$colorSetting->value = $data['colors'];
			$colorSetting->save();
		}

		return Redirect::back()->with('success', Lang::get('users.updateSettingsSuccessfull', array('login' => $user->login)));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy()
	{
		$data = [
			'password'            => Input::get('password'),
			'delete-confirmation' => Input::get('delete-confirmation'),
			'login'               => Auth::user()->login
		];

		$messages = [
    		'delete-confirmation.in' => Lang::get('users.writeDeleteHere'),
		];
		$validator = Validator::make($data, User::$rulesDestroy, $messages);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput(Input::except('password'));
		}
		else {
			unset($data['delete-confirmation']);
			if (!Auth::validate($data))
				return Redirect::back()->withErrors(array('password' => Lang::get('auth.passwordInvalid')))->withInput(Input::except('password'));
		}

		// Delete the user
		User::find(Auth::id())->delete();
		// Log him out
		Auth::logout();

		return Redirect::home()->with('success', Lang::get('users.deleteAccountSuccessfull'));
	}

}