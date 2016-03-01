<?php
/**
 * Mandrill Mail service provider.
 *
 * Provides the MandrillMailer to send emails via Mandrill API.
 */
namespace Nord\Lumen\Mandrill\Mailer;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Nord\Lumen\Mandrill\Mailer\Facades\MandrillMailer as MandrillMailerFacade;

/**
 * Class MandrillMailServiceProvider
 *
 * @package Nord\Lumen\Mandrill\Mailer
 */
class MandrillMailServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerContainerBindings($this->app);
        $this->registerFacades();
    }

    /**
     * Registers the container bindings.
     *
     * @param Container $container
     */
    protected function registerContainerBindings(Container $container)
    {
        $container->singleton('Nord\Lumen\Mandrill\Mailer\Contracts\MandrillMailer', function () {
            $config = config('mandrillmailer', []);

            return new MandrillMailer($config);
        });
    }

    /**
     * Registers the MandrillMailer facade.
     */
    protected function registerFacades()
    {
        if ( ! class_exists('MandrillMailer')) {
            class_alias(MandrillMailerFacade::class, 'MandrillMailer');
        }
    }
}
