# lumen-mandrill-mailer

Mandrill mailer for the Lumen PHP framework.

Send email messages via the Mandrill API, either with a template, or plain emails.

# Install

    // .env:
    MANDRILL_API_KEY=<ANY_VALID_API_KEY>
    MANDRILL_PRETEND=<TRUE|FALSE>
    
    
    // config/mandrillmailer.php
    return [
        'secret' => env('MANDRILL_API_KEY'),
        'pretend' => env('MANDRILL_PRETEND', false),
    ];

    
    // bootstrap/app.php
    ...
    $app->configure('mandrillmailer');
    ...
    $app->register(Nord\Lumen\Mandrill\Mailer\MandrillMailServiceProvider::class);

# Usage

You can add support for sending Mandrill emails to any part of your application. Use the `SendsEmails` trait to do so:

    ...
    use \Nord\Lumen\Mandrill\Mailer\App\SendsEmails;
    ...

Then later on in your code, you may use the functions `$this->sendRaw(MandrillMailMessage $message)` to send raw message,
or `$this->sendTemplate(MandrillMailMessage $message)` to send a template email. You will need to construct the message to
be sent:

    ...
    $message = new \Nord\Lumen\Mandrill\Mailer\Infrastructure\MandrillMailMessage(
        ['Recipient Name' => 'email@recipient.com'], // To address.
        ['From Name' => 'email@from.com'], // From address.
        'Email Subject', // Subject.
        [ // Message body.
            'html' => '<h1>Message body HTML</h1>',
            'text' => 'Message body plain text',
            'data' => [
                'templateVariableName' => 'TemplateVariableValue',
                'firstName' => 'First Name',
                'lastName' => 'Last Name',
                ...
            ],
        ],
        'template-name', // Mandrill template name. Optional.
        [
            'templateContent' // Template content. Optional.
        ],
        'cc@email.com', // CC Address. Optional.
        'bcc@email.com', // BCC Address. Optional.
        [ // Attachments. Optional.
            [
                'type' => 'image/png', // Required field.
                'name' => 'attachment1.png', // Required field.
                'content' => base64_encode(file_get_contents(/full/path/to/attachment1.png)), // Required field.
            ],
            [
                'type' => 'image/png',
                'name' => 'attachment2.png',
                'content' => base64_encode(file_get_contents(/full/path/to/attachment2.png)),
            ],
        ],
        [ // Headers. Optional
            'Reply-To' => 'reply-to@email.com',
        ],
        [ // Images. Optional.
            [
                'type' => 'image/png', // Required field.
                'name' => 'image1.png', // Required field.
                'content' => base64_encode(file_get_contents(/full/path/to/image1.png)), // Required field.
            ],
        ],
    );
    ...

# License
See [LICENSE](LICENSE).
