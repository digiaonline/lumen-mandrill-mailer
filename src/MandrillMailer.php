<?php
/**
 * Mandrill mailer.
 *
 * Sends emails using the Mandrill API client.
 */
namespace Nord\Lumen\Mandrill\Mailer;

use Illuminate\Support\Facades\Log;
use Mandrill;
use Nord\Lumen\Mandrill\Mailer\Contracts\MandrillMailer as MailerContract;
use Nord\Lumen\Mandrill\Mailer\Exceptions\InvalidMandrillConfigurationException;

/**
 * Class MandrillMailer.
 *
 * @package Nord\Lumen\Mandrill\Mailer
 */
class MandrillMailer implements MailerContract
{

    /**
     * @var Mandrill
     */
    protected $client;

    /**
     * @var bool
     */
    protected $pretend;

    /**
     * Class constructor.
     *
     * @param array $config Configuration for Mandrill client.
     *
     * @throws InvalidMandrillConfigurationException
     */
    public function __construct(array $config)
    {
        if ( ! isset( $config['secret'] ) || empty( $config['secret'] )) {
            throw new InvalidMandrillConfigurationException('Mandrill secret is not defined.');
        }
        $this->client  = new Mandrill($config['secret']);
        $this->pretend = $config['pretend'];
    }

    /**
     * @inheritdoc
     */
    public function sendTemplate($template, $templateContent, $message, $async = true)
    {
        if ($this->pretend) {
            Log::info(var_export($message, true));
            Log::info($template);
            $result = true;
        } else {
            $result = $this->client->messages->sendTemplate($template, $templateContent, $message, $async);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function send($message, $async = true)
    {
        if ($this->pretend) {
            Log::info($message);
            $result = true;
        } else {
            $result = $this->client->messages->send($message, $async);
        }

        return $result;
    }
}
