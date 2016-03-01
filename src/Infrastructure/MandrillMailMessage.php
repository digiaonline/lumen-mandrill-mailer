<?php
/**
 * MandrillMailMessage class.
 *
 * The actual message that is sent via the Mandrill API.
 */
namespace Nord\Lumen\Mandrill\Mailer\Infrastructure;

use Nord\Lumen\Mandrill\Mailer\Exceptions\InvalidArgumentException;
use Nord\Lumen\Mandrill\Mailer\Exceptions\MissingRequiredFieldException;

/**
 * Class MandrillMailMessage.
 *
 * @package Nord\Lumen\Mandrill\Mailer\Infrastructure
 */
class MandrillMailMessage
{

    /**
     * @var array
     */
    protected $to;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var array
     */
    protected $messageBody;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $templateContent;

    /**
     * @var array
     */
    protected $cc;

    /**
     * @var array
     */
    protected $bcc;

    /**
     * @var array
     */
    protected $attachments;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $images;

    /**
     * The message HTML for raw messages.
     *
     * @var string
     */
    protected $messageHtml;

    /**
     * The message text for raw messages.
     *
     * @var string
     */
    protected $messageText;

    /**
     * @param array       $to
     * @param array       $from
     * @param string      $subject
     * @param array       $messageBody
     * @param string|null $template
     * @param array       $templateContent
     * @param array       $cc
     * @param array       $bcc
     * @param array       $attachments
     * @param array       $headers
     * @param array       $images
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        $to,
        $from,
        $subject,
        $messageBody,
        $template = null,
        $templateContent = [],
        $cc = null,
        $bcc = null,
        array $attachments = [],
        array $headers = [],
        array $images = []
    ) {
        $this->setTo($to);
        $this->setSubject($subject);
        $this->setFrom($from);
        $this->setMessageBody($messageBody);
        $this->setTemplate($template);
        $this->setTemplateContent($templateContent);
        $this->setCc($cc);
        $this->setBcc($bcc);
        $this->setAttachments($attachments);
        $this->setHeaders($headers);
        $this->setImages($images);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function changeTemplate($template)
    {
        $this->setTemplate($template);
    }

    /**
     * @return array
     */
    public function getTemplateContent()
    {
        return $this->templateContent;
    }

    /**
     * @param array $templateContent
     */
    public function changeTemplateContent($templateContent)
    {
        $this->setTemplateContent($templateContent);
    }

    /**
     * @param string|null $type
     *
     * @return array
     */
    public function getTo($type = null)
    {
        switch ($type) {
            case 'name':
                $keys = array_keys($this->to);

                return array_pop($keys);
                break;
            case 'email':
                $values = array_values($this->to);

                return array_pop($values);
                break;
            default:
                return $this->to;
        }
    }

    /**
     * @param array $to
     */
    public function changeTo($to)
    {
        $this->setTo($to);
    }

    /**
     * @param string|null $type
     *
     * @return array
     */
    public function getCc($type = null)
    {
        switch ($type) {
            case 'name':
                $keys = array_keys($this->cc);

                return array_pop($keys);
                break;
            case 'email':
                $values = array_values($this->cc);

                return array_pop($values);
                break;
            default:
                return $this->cc;
        }
    }

    /**
     * @param array $cc
     */
    public function changeCc($cc)
    {
        $this->setCc($cc);
    }

    /**
     * @param string|null $type
     *
     * @return array
     */
    public function getBcc($type = null)
    {
        switch ($type) {
            case 'name':
                $keys = array_keys($this->bcc);

                return array_pop($keys);
                break;
            case 'email':
                $values = array_values($this->bcc);

                return array_pop($values);
                break;
            default:
                return $this->bcc;
        }
    }

    /**
     * @param array $bcc
     */
    public function changeBcc($bcc)
    {
        $this->setBcc($bcc);
    }

    /**
     * @return array
     */
    public function getMessageBody()
    {
        return $this->messageBody;
    }

    /**
     * @param array $messageBody
     */
    public function changeMessageBody($messageBody)
    {
        $this->setMessageBody($messageBody);
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function changeSubject($subject)
    {
        $this->setSubject($subject);
    }

    /**
     * @param string|null $type
     *
     * @return array
     */
    public function getFrom($type = null)
    {
        switch ($type) {
            case 'name':
                $keys = array_keys($this->from);

                return array_pop($keys);
                break;
            case 'email':
                $values = array_values($this->from);

                return array_pop($values);
                break;
            default:
                return $this->from;
        }
    }

    /**
     * @param array $from
     */
    public function changeFrom($from)
    {
        $this->setFrom($from);
    }

    /**
     * Construct the message array for use with Mandrill.
     *
     * @return array
     */
    public function constructMessageForMandrill()
    {
        $message = [];

        $message['subject']    = $this->subject;
        $message['from_name']  = $this->getFrom('name');
        $message['from_email'] = $this->getFrom('email');
        foreach ($this->getTo() as $name => $email) {
            $message['to'][] = ['name' => $name, 'email' => $email, 'type' => 'to'];
        }

        if ( ! empty( $this->messageHtml )) {
            $message['html'] = $this->messageHtml;
        }
        if ( ! empty( $this->messageText )) {
            $message['text'] = $this->messageText;
        }

        if ( ! empty( $this->headers )) {
            foreach ($this->headers as $header => $value) {
                $message['headers'][$header] = $value;
            }
        }

        if ( ! empty( $this->bcc )) {
            $message['bcc_address'] = $this->getBcc('email');
        }

        if ( ! empty( $this->attachments )) {
            foreach ($this->attachments as $attachment) {
                $message['attachments'][] = [
                    'type'    => $attachment['type'],
                    'name'    => $attachment['name'],
                    'content' => $attachment['content'],
                ];
            }
        }

        if ( ! empty( $this->images )) {
            foreach ($this->images as $image) {
                $message['images'][] = [
                    'type'    => $image['type'],
                    'name'    => $image['name'],
                    'content' => $image['content'],
                ];
            }
        }

        $message['merge_language']    = array_get($this->messageBody, 'merge_language', 'handlebars');
        $message['global_merge_vars'] = array_get($this->messageBody, 'global_merge_vars', []);
        foreach (array_get($this->messageBody, 'data', []) as $key => $value) {
            $message['global_merge_vars'][] = ['name' => $key, 'content' => $value];
        }

        return $message;
    }

    /**
     * Get a part of the message body.
     *
     * @param string $part
     *
     * @return string|null
     */
    protected function getMessageBodyPart($part)
    {
        return isset( $this->messageBody[$part] ) ? $this->messageBody[$part] : null;
    }

    /**
     * @param string $template
     */
    protected function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param array $templateContent
     */
    protected function setTemplateContent($templateContent)
    {
        $this->templateContent = $templateContent;
    }

    /**
     * @param array $to
     *
     * @throws InvalidArgumentException
     */
    protected function setTo($to)
    {
        if (empty( $to )) {
            throw new InvalidArgumentException('To cannot be empty.');
        }

        if ( ! is_array($to)) {
            $to = [$to];
        }

        $this->to = $to;
    }

    /**
     * @param array $cc
     */
    protected function setCc($cc)
    {
        if ( ! empty( $cc )) {
            if ( ! is_array($cc)) {
                $cc = [$cc];
            }
            $this->cc = $cc;
        }
    }

    /**
     * @param array $bcc
     */
    protected function setBcc($bcc)
    {
        if ( ! empty( $bcc )) {
            if ( ! is_array($bcc)) {
                $bcc = [$bcc];
            }
            $this->bcc = $bcc;
        }
    }

    /**
     * @param array $attachments
     *
     * @throws MissingRequiredFieldException
     */
    protected function setAttachments(array $attachments = [])
    {
        if ( ! empty( $attachments )) {
            $this->checkRequiredFields($attachments, ['type', 'name', 'content']);
            $this->attachments = $attachments;
        }
    }

    /**
     * @param array $headers
     */
    protected function setHeaders(array $headers = [])
    {
        if ( ! empty( $headers )) {
            $this->headers = $headers;
        }
    }

    /**
     * @param array $images
     *
     * @throws MissingRequiredFieldException
     */
    protected function setImages(array $images = [])
    {
        if ( ! empty( $images )) {
            $this->checkRequiredFields($images, ['type', 'name', 'content']);
            $this->images = $images;
        }
    }

    /**
     * @param array $messageBody
     */
    protected function setMessageBody($messageBody)
    {
        if (isset( $messageBody['text'] )) {
            $this->messageText = $messageBody['text'];
            unset( $messageBody['text'] );
        }
        if (isset( $messageBody['html'] )) {
            $this->messageHtml = $messageBody['html'];
            unset( $messageBody['html'] );
        }

        $this->messageBody = $messageBody;
    }

    /**
     * @param string $subject
     *
     * @throws InvalidArgumentException
     */
    protected function setSubject($subject)
    {
        if (empty( $subject )) {
            throw new InvalidArgumentException('Subject cannot be empty.');
        }
        $this->subject = $subject;
    }

    /**
     * @param array $from
     */
    protected function setFrom($from)
    {
        if ( ! is_array($from)) {
            $from = [$from];
        }
        $this->from = $from;
    }

    /**
     * Checks if the given lists contains given required fields.
     *
     * @param array $lists    Array of arrays.
     * @param array $required List of required fields.
     *
     * @throws MissingRequiredFieldException
     */
    protected function checkRequiredFields(array $lists, array $required)
    {
        foreach ($required as $requiredField) {
            foreach ($lists as $list) {
                if ( ! isset( $list[$requiredField] )) {
                    throw new MissingRequiredFieldException(sprintf('Field %s is missing', $requiredField));
                }
            }
        }
    }
}
