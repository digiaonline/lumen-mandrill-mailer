<?php
/**
 * Mandrill mail service.
 *
 * Handles sending of the Mandrill template emails and raw email messages.
 */
namespace Nord\Lumen\Mandrill\Mailer\Services;

use Illuminate\Support\Facades\Log;
use Nord\Lumen\Mandrill\Mailer\Facades\MandrillMailer;
use Nord\Lumen\Mandrill\Mailer\Infrastructure\MandrillMailMessage;

/**
 * Class MandrillMailService.
 *
 * @package Nord\Lumen\Mandrill\Mailer\Services
 */
class MandrillMailService
{

    /**
     * @var array
     */
    protected static $info = [];

    /**
     * Send the email message with given template.
     *
     * @param MandrillMailMessage $message The message to send.
     *
     * @return bool True if email sent, false if sending message rejected, or invalid message.
     */
    public function sendTemplate(MandrillMailMessage $message)
    {
        $result = MandrillMailer::sendTemplate($message->getTemplate(), $message->getTemplateContent(),
            $message->constructMessageForMandrill());

        if (is_array($result) && count($result) > 0) {
            self::$info = reset($result);

            if (in_array(self::$info['status'], ['rejected', 'invalid'])) {
                Log::error(sprintf('Could not send email message to %s, reject_reason: %s', self::$info['email'],
                    self::$info['reject_reason']));

                return false;
            }
        }

        return true;
    }

    /**
     * Send a raw message with Mandrill.
     *
     * @param MandrillMailMessage $message The message to send.
     *
     * @return bool True if email sent, false if sending message rejected, or invalid message.
     */
    public function send(MandrillMailMessage $message)
    {
        $result = MandrillMailer::send($message->constructMessageForMandrill());

        if (is_array($result) && count($result) > 0) {
            self::$info = reset($result);

            if (in_array(self::$info['status'], ['rejected', 'invalid'])) {
                Log::error(sprintf('Could not send email message to %s, reject_reason: %s', self::$info['email'],
                    self::$info['reject_reason']));

                return false;
            }
        }

        return true;
    }

    /**
     * Get the info for latest email sending.
     *
     * @return array
     */
    public function getInfo()
    {
        return self::$info;
    }

    /**
     * Get the latest reject reason.
     *
     * @return string|null
     */
    public function getRejectReason()
    {
        if (isset( self::$info['reject_reason'] )) {
            return self::$info['reject_reason'];
        }

        return null;
    }
}
