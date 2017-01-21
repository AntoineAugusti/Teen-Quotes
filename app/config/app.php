<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

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

    'debug' => false,

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

    'url'           => 'http://teen-quotes.com',

    'domain'        => 'teen-quotes.com',

    'domainAPI'     => 'api.teen-quotes.com',
    'domainStories' => 'stories.teen-quotes.com',
    'domainAdmin'   => 'admin.teen-quotes.com',

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

    'key'    => 'zXPYTseYZiFjua90HVGJMro7CYb8USRg',
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

    'providers' => [

        'Antoineaugusti\LaravelEasyrec\LaravelEasyrecServiceProvider',
        'Antoineaugusti\LaravelSentimentAnalysis\LaravelSentimentAnalysisServiceProvider',
        'Bugsnag\BugsnagLaravel\BugsnagLaravelServiceProvider',
        'Buonzz\GeoIP\Laravel4\ServiceProviders\GeoIPServiceProvider',
        'GrahamCampbell\HTMLMin\HTMLMinServiceProvider',
        'Healey\Robots\RobotsServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Auth\Reminders\ReminderServiceProvider',
        'Illuminate\Cache\CacheServiceProvider',
        'Illuminate\Cookie\CookieServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Database\MigrationServiceProvider',
        'Illuminate\Database\SeedServiceProvider',
        'Illuminate\Encryption\EncryptionServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
        'Illuminate\Hashing\HashServiceProvider',
        'Illuminate\Html\HtmlServiceProvider',
        'Illuminate\Log\LogServiceProvider',
        'Illuminate\Pagination\PaginationServiceProvider',
        'Illuminate\Queue\QueueServiceProvider',
        'Illuminate\Redis\RedisServiceProvider',
        'Illuminate\Remote\RemoteServiceProvider',
        'Illuminate\Routing\ControllerServiceProvider',
        'Illuminate\Session\CommandsServiceProvider',
        'Illuminate\Session\SessionServiceProvider',
        'Illuminate\Translation\TranslationServiceProvider',
        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Workbench\WorkbenchServiceProvider',
        'Indatus\Dispatcher\ServiceProvider',
        'Jenssegers\Agent\AgentServiceProvider',
        'Laracasts\Utilities\UtilitiesServiceProvider',
        'Laracasts\Validation\ValidationServiceProvider',
        'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
        'Philf\Setting\SettingServiceProvider',
        'Philo\Translate\TranslateServiceProvider',
        'TeenQuotes\AdminPanel\AdminPanelServiceProvider',
        'TeenQuotes\Api\V1\ApiServiceProvider',
        'TeenQuotes\Auth\AuthServiceProvider',
        'TeenQuotes\Comments\CommentsServiceProvider',
        'TeenQuotes\Countries\CountriesServiceProvider',
        'TeenQuotes\Mail\MailServiceProvider',
        'TeenQuotes\Newsletters\NewsletterListServiceProvider',
        'TeenQuotes\Newsletters\NewslettersServiceProvider',
        'TeenQuotes\Notifiers\AdminNotifierServiceProvider',
        'TeenQuotes\Pages\PagesServiceProvider',
        'TeenQuotes\Quotes\QuotesServiceProvider',
        'TeenQuotes\Robots\RobotsServiceProvider',
        'TeenQuotes\Settings\SettingsServiceProvider',
        'TeenQuotes\Stories\StoriesServiceProvider',
        'TeenQuotes\Tags\TagsServiceProvider',
        'TeenQuotes\Tools\ToolsServiceProvider',
        'TeenQuotes\Users\UsersServiceProvider',
        'Thomaswelton\LaravelGravatar\LaravelGravatarServiceProvider',
        'Way\Generators\GeneratorsServiceProvider',
    ],

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

    'aliases' => [

        'Agent'               => 'Jenssegers\Agent\Facades\Agent',
        'App'                 => 'Illuminate\Support\Facades\App',
        'Artisan'             => 'Illuminate\Support\Facades\Artisan',
        'Auth'                => 'Illuminate\Support\Facades\Auth',
        'AuthorizationServer' => 'LucaDegasperi\OAuth2Server\Facades\AuthorizationServerFacade',
        'BaseController'      => 'TeenQuotes\Tools\BaseController',
        'Blade'               => 'Illuminate\Support\Facades\Blade',
        'Bugsnag'             => 'Bugsnag\BugsnagLaravel\BugsnagFacade',
        'Cache'               => 'Illuminate\Support\Facades\Cache',
        'Carbon'              => 'Carbon\Carbon',
        'ClassLoader'         => 'Illuminate\Support\ClassLoader',
        'Config'              => 'Illuminate\Support\Facades\Config',
        'Controller'          => 'Illuminate\Routing\Controller',
        'Cookie'              => 'Illuminate\Support\Facades\Cookie',
        'Crypt'               => 'Illuminate\Support\Facades\Crypt',
        'DB'                  => 'Illuminate\Support\Facades\DB',
        'Easyrec'             => 'Antoineaugusti\LaravelEasyrec\Facades\LaravelEasyrec',
        'Eloquent'            => 'Illuminate\Database\Eloquent\Model',
        'Event'               => 'Illuminate\Support\Facades\Event',
        'File'                => 'Illuminate\Support\Facades\File',
        'Form'                => 'Illuminate\Support\Facades\Form',
        'GeoIP'               => 'Buonzz\GeoIP\Laravel4\Facades\GeoIP',
        'Gravatar'            => 'Thomaswelton\LaravelGravatar\Facades\Gravatar',
        'Hash'                => 'Illuminate\Support\Facades\Hash',
        'HTML'                => 'Illuminate\Support\Facades\HTML',
        'Input'               => 'Illuminate\Support\Facades\Input',
        'Lang'                => 'Illuminate\Support\Facades\Lang',
        'LaraSetting'         => 'Philf\Setting\Facades\Setting',
        'Log'                 => 'Illuminate\Support\Facades\Log',
        'Mail'                => 'Illuminate\Support\Facades\Mail',
        'MailSwitcher'        => 'TeenQuotes\Mail\MailSwitcher',
        'Paginator'           => 'Illuminate\Support\Facades\Paginator',
        'Password'            => 'Illuminate\Support\Facades\Password',
        'Queue'               => 'Illuminate\Support\Facades\Queue',
        'Redirect'            => 'Illuminate\Support\Facades\Redirect',
        'Redis'               => 'Illuminate\Support\Facades\Redis',
        'Request'             => 'Illuminate\Support\Facades\Request',
        'ResourceServer'      => 'LucaDegasperi\OAuth2Server\Facades\ResourceServerFacade',
        'Response'            => 'Illuminate\Support\Facades\Response',
        'Route'               => 'Illuminate\Support\Facades\Route',
        'ScheduledCommand'    => 'Indatus\Dispatcher\Scheduling\ScheduledCommand',
        'Schema'              => 'Illuminate\Support\Facades\Schema',
        'Seeder'              => 'Illuminate\Database\Seeder',
        'SentimentAnalysis'   => 'Antoineaugusti\LaravelSentimentAnalysis\Facades\SentimentAnalysis',
        'Session'             => 'Illuminate\Support\Facades\Session',
        'SSH'                 => 'Illuminate\Support\Facades\SSH',
        'Str'                 => 'Illuminate\Support\Str',
        'TextTools'           => 'TeenQuotes\Tools\TextTools',
        'Toloquent'           => 'TeenQuotes\Database\Toloquent',
        'URL'                 => 'Illuminate\Support\Facades\URL',
        'Validator'           => 'Illuminate\Support\Facades\Validator',
        'View'                => 'Illuminate\Support\Facades\View',
    ],

    /*
    |--------------------------------------------------------------------------
    | App variables
    |--------------------------------------------------------------------------
    |
    |
    */

    'comments.nbCommentsPerPage'     => 10,

    'quotes.nbQuotesToPublishPerDay' => 3,

    'quotes.nbQuotesPerPage'         => 10,

    'quotes.maxSubmitPerDay'         => 5,

    // International Association for Suicide Prevention
    'quotes.moderationURLHelp'         => 'http://www.iasp.info/resources/Crisis_Centres/',

    'newsletters.nbQuotesToSendWeekly' => 10,

    'newsletters.nbQuotesToSendDaily'  => 2,

    'users.avatar.default'             => 'https://account.teen-quotes.com/assets/images/chat.png',
    'users.avatarPath'                 => 'uploads/avatar',
    'users.avatarWidth'                => 200,
    'users.avatarHeight'               => 200,

    'users.colorsAvailableQuotesPublished'      => [
        'blue',
        'green',
        'purple',
        'red',
        'orange',
    ],

    'users.defaultColorQuotesPublished' => 'blue',

    'users.nbQuotesPerPage'             => 5,

    'search.maxResultsPerCategory'      => 10,

    'stories.nbStoriesPerPage'          => 5,
];
