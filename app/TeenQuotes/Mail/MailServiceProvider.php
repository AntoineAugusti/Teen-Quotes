<?php

namespace TeenQuotes\Mail;

use TeenQuotes\Mail\Transport\MandrillTransport;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    public function register()
    {
        $me = $this;

        $this->registerViewComposers();

        $this->app->bindShared('mailer', function ($app) use ($me) {
            $me->registerSwiftMailer();

            // Once we have create the mailer instance, we will set a container instance
            // on the mailer. This allows us to resolve mailer classes via containers
            // for maximum testability on said classes instead of passing Closures.
            $mailer = new Mailer(
                $app['view'], $app['swift.mailer'], $app['events']
            );

            $this->setMailerDependencies($mailer, $app);

            // If a "from" address is set, we will set it on the mailer so that all mail
            // messages sent by the applications will utilize the same "from" address
            // on each one, which makes the developer's life a lot more convenient.
            $from = $app['config']['mail.from'];

            if (is_array($from) && isset($from['address'])) {
                $mailer->alwaysFrom($from['address'], $from['name']);
            }

            // Here we will determine if the mailer should be in "pretend" mode for this
            // environment, which will simply write out e-mail to the logs instead of
            // sending it over the web, which is useful for local dev environments.
            $pretend = $app['config']->get('mail.pretend', false);

            $mailer->pretend($pretend);

            return $mailer;
        });
    }

    /**
     * Register the Mandrill Swift Transport instance.
     *
     * @param array $config
     */
    protected function registerMandrillTransport($config)
    {
        $mandrill = $this->app['config']->get('services.mandrill', []);

        $this->app->bindShared('swift.transport', function () use ($mandrill) {
            return new MandrillTransport($mandrill['secret']);
        });
    }

    private function registerViewComposers()
    {
        // Welcome email
        $this->app['view']->composer([
            'emails.welcome',
        ], 'TeenQuotes\Mail\Composers\WelcomeViewComposer');
    }
}
