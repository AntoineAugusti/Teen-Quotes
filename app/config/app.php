<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => true,

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://localhost',

	'domain' => 'teen-quotes.com',

	'domainAPI' => 'api.teen-quotes.com',

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'UTC',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Application Fallback Locale
	|--------------------------------------------------------------------------
	|
	| The fallback locale determines the locale to use when the current one
	| is not available. You may change the value to correspond to any of
	| the language folders that are provided through your application.
	|
	*/

	'fallback_locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => 'zXPYTseYZiFjua90HVGJMro7CYb8USRg',
	'cipher' => MCRYPT_RIJNDAEL_256,

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		// 'Illuminate\Mail\MailServiceProvider',
		'TeenQuotes\Mail\MailServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Remote\RemoteServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',
		'Way\Generators\GeneratorsServiceProvider',
		'Fitztrev\LaravelHtmlMinify\LaravelHtmlMinifyServiceProvider',
		'Laracasts\Utilities\UtilitiesServiceProvider',
		'Indatus\Dispatcher\ServiceProvider',
		'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
	),

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
	*/

	'manifest' => storage_path().'/meta',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => array(

		'App'              => 'Illuminate\Support\Facades\App',
		'Artisan'          => 'Illuminate\Support\Facades\Artisan',
		'Auth'             => 'Illuminate\Support\Facades\Auth',
		'Blade'            => 'Illuminate\Support\Facades\Blade',
		'Cache'            => 'Illuminate\Support\Facades\Cache',
		'ClassLoader'      => 'Illuminate\Support\ClassLoader',
		'Config'           => 'Illuminate\Support\Facades\Config',
		'Controller'       => 'Illuminate\Routing\Controller',
		'Cookie'           => 'Illuminate\Support\Facades\Cookie',
		'Crypt'            => 'Illuminate\Support\Facades\Crypt',
		'DB'               => 'Illuminate\Support\Facades\DB',
		'Eloquent'         => 'Illuminate\Database\Eloquent\Model',
		'Event'            => 'Illuminate\Support\Facades\Event',
		'File'             => 'Illuminate\Support\Facades\File',
		'Form'             => 'Illuminate\Support\Facades\Form',
		'Hash'             => 'Illuminate\Support\Facades\Hash',
		'HTML'             => 'Illuminate\Support\Facades\HTML',
		'Input'            => 'Illuminate\Support\Facades\Input',
		'Lang'             => 'Illuminate\Support\Facades\Lang',
		'Log'              => 'Illuminate\Support\Facades\Log',
		'Mail'             => 'Illuminate\Support\Facades\Mail',
		'Paginator'        => 'Illuminate\Support\Facades\Paginator',
		'Password'         => 'Illuminate\Support\Facades\Password',
		'Queue'            => 'Illuminate\Support\Facades\Queue',
		'Redirect'         => 'Illuminate\Support\Facades\Redirect',
		'Redis'            => 'Illuminate\Support\Facades\Redis',
		'Request'          => 'Illuminate\Support\Facades\Request',
		'Response'         => 'Illuminate\Support\Facades\Response',
		'Route'            => 'Illuminate\Support\Facades\Route',
		'Schema'           => 'Illuminate\Support\Facades\Schema',
		'Seeder'           => 'Illuminate\Database\Seeder',
		'Session'          => 'Illuminate\Support\Facades\Session',
		'SSH'              => 'Illuminate\Support\Facades\SSH',
		'Str'              => 'Illuminate\Support\Str',
		'URL'              => 'Illuminate\Support\Facades\URL',
		'Validator'        => 'Illuminate\Support\Facades\Validator',
		'View'             => 'Illuminate\Support\Facades\View',
		'Carbon'           => 'Carbon\Carbon',
		'ScheduledCommand' => '\Indatus\Dispatcher\Scheduling\ScheduledCommand',
		'AuthorizationServer' => 'LucaDegasperi\OAuth2Server\Facades\AuthorizationServerFacade',
		'ResourceServer' => 'LucaDegasperi\OAuth2Server\Facades\ResourceServerFacade',

	),

	/*
	|--------------------------------------------------------------------------
	| App variables
	|--------------------------------------------------------------------------
	|
	|
	*/

		'quotes.nbQuotesToPublishPerDay'   => 5,

		'quotes.nbQuotesPerPage'           => 10,

		'newsletters.nbQuotesToSendWeekly' => 10,

		'newsletters.nbQuotesToSendDaily'  => 2,

		'users.avatarPath'                 => 'uploads/avatar',

		'users.colorsQuotesPublished'      => [
			'blue'                             => ['#5C97BF', '#2574A9', '#3A539B', '#1E8BC3', '#19B5FE', '#89C4F4', '#3498DB', '#52B3D9'],
			'green'                            => ['#26C281', '#26C281', '#3FC380', '#2ECC71', '#4DAF7C', '#03A678', '#00B16A', '#1BBC9B'],
			'purple'                           => ['#913D88', '#9A12B3', '#BF55EC', '#BE90D4', '#8E44AD', '#9B59B6'],
			'red'                              => ['#D91E18', '#96281B', '#EF4836', '#D64541', '#F22613', '#E74C3C', '#CF000F'],
			'orange'                           => ['#E87E04', '#F2784B', '#F9690E', '#F27935', '#E67E22', '#D35400', '#F39C12', '#F5AB35'],
		],

		'users.defaultColorQuotesPublished' => 'blue',

		'users.nbQuotesPerPage'             => 5,

		'search.maxResultsPerCategory'      => 10,
);