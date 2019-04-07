<?php

return [
    'desk' => [
        /*
        |--------------------------------------------------------------------------
        | Teamwork Desk Key
        |--------------------------------------------------------------------------
        |
        | The Teamwork Desk API Key can be generated at:
        | https://your-domain.teamwork.com/desk/#myprofile/apikeys
        |
        */
        'key' => env('TEAMWORK_DESK_KEY'),

        /*
        |--------------------------------------------------------------------------
        | Teamwork Desk Domain Name
        |--------------------------------------------------------------------------
        |
        | The domain is the site address you have set on the Teamwork account.
        | To find the domain name just login to http://teamwork.com.
        | Then you will see the browser URL changing to:
        | https://your-domain.teamwork.com/launchpad/welcome
        |
        */
        'domain' => env('TEAMWORK_DESK_DOMAIN'),
    ],
];
