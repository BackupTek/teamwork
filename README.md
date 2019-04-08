# Teamwork PHP SDK - Laravel Framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digitalequation/teamwork.svg?style=flat-square)](https://packagist.org/packages/digitalequation/teamwork)
[![Build Status](https://img.shields.io/travis/digitalequation/teamwork/master.svg?style=flat-square)](https://travis-ci.org/digitalequation/teamwork)
[![StyleCI](https://github.styleci.io/repos/179847016/shield?branch=master)](https://github.styleci.io/repos/179847016)
[![Quality Score](https://img.shields.io/scrutinizer/g/digitalequation/teamwork.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalequation/teamwork)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalequation/teamwork.svg?style=flat-square)](https://packagist.org/packages/digitalequation/teamwork)
[![License](https://img.shields.io/packagist/l/digitalequation/teamwork.svg?style=flat-square)](https://github.com/digitalequation/teamwork/blob/master/LICENSE.md)

A PHP Laravel wrapper library for Teamwork Desk, Teamwork Help Docs and Teamwork Tickets API's.  
This package was built for our internal projects and may not be the right one for you but you are free to use it if you like.
## Installation

You can install the package via composer:
```bash
composer require digitalequation/teamwork
```

Run the package install command:
```bash
php artisan teamwork:install
```
This will publish and register the TeamworkServiceProvider and will also generate a config file `config/teamwork.php`.

```php
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
```

You can edit this file directly but we recommend to add your settings in the `.env` file.

If you edit the config file and want to restore the defaults run:
```bash
php artisan teamwork:publish
```

Add your Teamwork Desk API Key and Domain to the `.env` file:
```bash
TEAMWORK_DESK_KEY=--YOUR-TEAMWORK-DESK-KEY--
TEAMWORK_DESK_DOMAIN=--YOUR-TEAMWORK-DESK-DOMAIN--
```

## Usage

Example using `facade`:
```php
use Teamwork;

$response = Teamwork::desk()->me();
```

Example using `dependency injection`:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DigitalEquation\Teamwork\Teamwork;

class TeamworkController extends Controller
{
    protected $teamwork;

    public function __construct(Teamwork $teamwork)
    {
        $this->teamwork = $teamwork;
    }
    
    public function getMe()
    {
        try {
            $response = $this->teamwork->desk()->me();
            
            // do something with the response data...
        } catch (\Exception $e) {
            // do something with the error...
        }
    }
    
    // other methods
```

For all of the examples listed bellow we will use the `Teamwork` facade.

### Teamwork Desk
___
Get current user data:
```php
$response = Teamwork::desk()->me();
```

Get all Teamwork Desk Inboxes:
```php
$response = Teamwork::desk()->inboxes();
```

Get an inbox by name:
```php
$response = Teamwork::desk()->inbox('Inbox Name');
```

Upload a file:
```php
$teamworkUser = Teamwork::desk()->me();

$response = Teamwork::desk()->upload($teamworkUser['id'], $request->file);
```

Example response for file upload:
```php
[
    'id'        => 1312, // the uploaded file id on Teamwork
    'url'       => 'http://...', // the URL of the image
    'extension' => 'jpg',
    'name'      => 'Some File Name',
    'size'      => '42342', // the image size in kb
]
```

**TIP:** Surround your `Teamwork` calls in `try-catch` blocks to capture any possible thrown exception. 

### Teamwork Tickets
___
Get ticket priorities:
```php
$response = Teamwork::tickets()->priorities();
```

Get a ticket by id:
```php
$response = Teamwork::tickets()->ticket($ticketId);
```

Get a list of tickets for a customer/user:
```php
$response = Teamwork::tickets()->customer($customerId);
```

Post/Send a ticket:
```php
$data = [
    'assignedTo'          => 5465, // the id of the assigned user on ticket
    'inboxId'             => 5545, // the inbox id where the ticket will be sent
    'tags'                => 'Test ticket',
    'priority'            => 'low',
    'status'              => 'active',
    'source'              => 'Email (Manual)',
    'customerFirstName'   => 'Test', // sender's first name
    'customerLastName'    => 'User', // sender's last name
    'customerEmail'       => 'test.user@email.com', // sender's email
    'customerPhoneNumber' => '', // sender's phone number
    'subject'             => 'Ticket Subject',
    'previewTest'         => 'Ticket excerpt.',
    'message'             => 'The ticket body...',
];

$response = Teamwork::tickets()->post($data);
```

Reply to a ticket:
```php
$data = [
    'ticketId'   => 2201568, // the ticket id where the reply will be sent
    'body'       => 'Reply TEST on ticket.',
    'customerId' => 65465,
];

$response = Teamwork::tickets()->reply($data);
```

### Teamwork Help Docs
___
Get Help Docs list of sites:
```php
$response = Teamwork::helpDesk()->getSites();
```

Get a Help Docs site by id:
```php
$response = Teamwork::helpDesk()->getSite($siteId);
```

Get all categories within a site:
```php
$response = Teamwork::helpDesk()->getSitesCategories($siteId);
```

Get articles within a category:
```php
$response = Teamwork::helpDesk()->getCategoryArticles($categoryId, $pageId);
```

Get a list of site articles:
```php
$response = Teamwork::helpDesk()->getSiteArticles($siteId, $pageId);
```

Get a single article:
```php
$response = Teamwork::helpDesk()->getArticle($articleId);
```

Get multiple articles by id's:
```php
$response = Teamwork::helpDesk()->getArticles($articleIDs);
```

### Testing
``` bash
composer test
```
This will also generate a coverage report that is accessible on the `build` directory, `coverage` and open the `index.html` file to see the results.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email robert@thebug.ro instead of using the issue tracker.

## Credits

- [Robert Cristian Chiribuc](https://github.com/chiribuc)
- [Marcel Mihai Bontaș](https://github.com/kirov117)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
