# Teamwork PHP SDK - Laravel Framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digitalequation/teamwork.svg?style=flat-square)](https://packagist.org/packages/digitalequation/teamwork)
[![Build Status](https://img.shields.io/travis/digitalequation/teamwork/master.svg?style=flat-square)](https://travis-ci.org/digitalequation/teamwork)
[![Quality Score](https://img.shields.io/scrutinizer/g/digitalequation/teamwork.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalequation/teamwork)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalequation/teamwork.svg?style=flat-square)](https://packagist.org/packages/digitalequation/teamwork)
[![License](https://img.shields.io/packagist/l/digitalequation/teamwork.svg?style=flat-square)](https://github.com/digitalequation/teamwork/blob/master/LICENSE.md)

A PHP Laravel wrapper library for Teamwork Desk, Teamwork Help Docs and Teamwork Tickets API's.

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

Since we are using Laravel we will inject the `Teamwork` class as a dependency to our `TeamworkController` constructor so we could have access to it on every method:
``` php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DigitalEquation\Teamwork\Teamwork;

class TeamworkController extends Controller
{
    /** @var \DigitalEquation\Teamwork */
    protected $teamwork;

    /**
     * TestController constructor.
     *
     * @param Teamwork $teamwork
     */
    public function __construct(Teamwork $teamwork)
    {
        $this->teamwork = $teamwork;
    }

    // other methods
```

### Teamwork Desk
___
Get current user data:
```php
try {
    $response = $this->teamwork->desk()->me();

    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get all Teamwork Desk Inboxes:
```php
try {
    $response = $this->teamwork->desk()->inboxes();

    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get an inbox by name:
```php
try {
    $response = $this->teamwork->desk()->inbox('Inbox Name');

    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Upload a file:
```php
public function postUploadAttachment(Request $request)
{
    if (!$request->has('file')) {
        // throw an error or something...
    }

    try {
        $user = $this->teamwork->desk()->me();
        $user = json_decode($user);

        $response = $this->teamwork->desk()->upload($user->id, $request->file);

        // example response
        [
            'id' => 1312, // the uploaded file id on Teamwork
            'file' => [
                'id' => 1312,
                'url'       => 'http://...', // the URL of the image
                'extension' => 'jpg',
                'name'      => 'Some File Name',
                'size'      => '42342', // the image size in kb
            ]
        ]

        // do something with the response data...
    } catch (\Exception $e) {
        // do something with the error...
    }
}
```

### Teamwork Tickets
___
Get ticket priorities:
```php
try {
    $response = $this->teamwork->tickets()->priorities();

    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get a ticket by id:
```php
try {
    $response = $this->teamwork->tickets()->ticket($ticketId);

    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get a list of tickets for a customer/user:
```php
try {
    $response = $this->teamwork->tickets()->customer($customerId);

    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Post/Send a ticket:
```php
try {
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

    $response = $this->teamwork->tickets()->post($data);

    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Reply to a ticket:
```php
try {
    $data = [
        'ticketId'   => 2201568, // the ticket id where the reply will be sent
        'body'       => 'Reply TEST on ticket.',
        'customerId' => 65465,
    ];

    $response = $this->teamwork->tickets()->reply($data);
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

### Teamwork Help Docs
___
Get Help Docs list of sites:
```php
try {
    $response = $this->teamwork->helpDesk()->getSites();
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get a Help Docs site by id:
```php
try {
    $response = $this->teamwork->helpDesk()->getSite($siteId);
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get all categories within a site:
```php
try {
    $response = $this->teamwork->helpDesk()->getSitesCategories($siteId);
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get articles within a category:
```php
try {
    $response = $this->teamwork->helpDesk()->getCategoryArticles($categoryId, $pageId);
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get a list of site articles:
```php
try {
    $response = $this->teamwork->helpDesk()->getSiteArticles($siteId, $pageId);
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get a single article:
```php
try {
    $response = $this->teamwork->helpDesk()->getArticle($articleId);
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
```

Get multiple articles by id's:
```php
try {
    $response = $this->teamwork->helpDesk()->getArticles($articleIDs);
    // do something with the response data...
} catch (\Exception $e) {
    // do something with the error...
}
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
