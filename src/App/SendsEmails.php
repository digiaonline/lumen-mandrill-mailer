<?php
/**
 * Trait to be used for email sending.
 */
namespace Nord\Lumen\Mandrill\Mailer\App;

use Nord\Lumen\Mandrill\Mailer\Infrastructure\MandrillMailMessage;
use Nord\Lumen\Mandrill\Mailer\Services\MandrillMailService;

/**
 * Trait SendsEmails.
 *
 * @package Nord\Lumen\Mandrill\Mailer\App
 */
trait SendsEmails
{

    /**
     * Send email with Mandrill template.
     *
     * @param MandrillMailMessage $message The message to send.
     *
     * @return bool
     */
    protected function sendTemplate(MandrillMailMessage $message)
    {
        return $this->getMailService()->sendTemplate($message);
    }

    /**
     * Send a raw email with Mandrill.
     *
     * @param MandrillMailMessage $message The message to send.
     *
     * @return bool
     */
    protected function sendRaw(MandrillMailMessage $message)
    {
        return $this->getMailService()->send($message);
    }

    /**
     * Gets the mail service.
     *
     * @return MandrillMailService
     */
    protected function getMailService()
    {
        return app(MandrillMailService::class);
    }
}
