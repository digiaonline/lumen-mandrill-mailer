<?php
/**
 * Interface for sending email.
 */
namespace Nord\Lumen\Mandrill\Mailer\Contracts;

/**
 * Interface MandrillMailer.
 *
 * @package Nord\Lumen\Mandrill\Mailer\Contracts
 */
interface MandrillMailer
{

    /**
     * Sends an email message with given Mandrill template name.
     *
     * @param string $template
     * @param array  $templateContent
     * @param array  $message
     * @param bool   $async
     *
     * @return array
     */
    public function sendTemplate($template, $templateContent, $message, $async = true);

    /**
     * Sends a raw email via Mandrill.
     *
     * @param array $message
     * @param bool  $async
     *
     * @return array|bool
     */
    public function send($message, $async = true);
}
