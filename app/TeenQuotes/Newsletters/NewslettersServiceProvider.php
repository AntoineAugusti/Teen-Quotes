<?php namespace TeenQuotes\Newsletters;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class NewslettersServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerBindings();
		$this->registerCommands();
	}

	private function registerBindings()
	{
		$namespace = $this->getBaseNamespace().'Repositories';
		
		$this->app->bind(
			$namespace.'\NewsletterRepository',
			$namespace.'\DbNewsletterRepository'
		);
	}

	private function registerCommands()
	{
		$commandName = $this->getBaseNamespace().'Console\SendNewsletterCommand';

		$this->app->bindShared('newsletters.console.sendNewsletter', function($app) use($commandName)
		{
			return $app->make($commandName);
		});

		$this->commands('newsletters.console.sendNewsletter');
	}

	private function getBaseNamespace()
	{
		$reflection = new ReflectionClass(self::class);
		return $reflection->getNamespaceName().'\\';
	}
}