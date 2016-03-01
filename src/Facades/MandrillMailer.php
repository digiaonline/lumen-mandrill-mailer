<?php
/**
 * Facade for the MandrillMailer.
 */
namespace Nord\Lumen\Mandrill\Mailer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MandrillMailer.
 *
 * @package Nord\Lumen\Mandrill\Mailer\Facades
 *
 * @method static sendTemplate( $template, $templateContent, $message, $async = true )
 * @method static send( $message, $async = true )
 */
class MandrillMailer extends Facade
{

    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return \Nord\Lumen\Mandrill\Mailer\Contracts\MandrillMailer::class;
    }
}
