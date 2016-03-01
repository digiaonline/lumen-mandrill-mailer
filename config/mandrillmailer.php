<?php

return [

    /*
     * Mandrill API key.
     */
    'secret' => env('MANDRILL_API_KEY'),
    /*
     * Pretend to send email. If set to true, will log to lumen.log with log level info.
     */
    'pretend' => env('MANDRILL_PRETEND', false),

];
