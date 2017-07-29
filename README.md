# Laravel YouTrack SDK

![cog-laravel-youtrack-sdk](https://cloud.githubusercontent.com/assets/1849174/26226734/98de7ac6-3c36-11e7-9017-4c0f2151ec81.png)

<p align="center">
<a href="https://travis-ci.org/cybercog/laravel-youtrack-sdk"><img src="https://img.shields.io/travis/cybercog/laravel-youtrack-sdk/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://styleci.io/repos/91741303"><img src="https://styleci.io/repos/91741303/shield" alt="StyleCI"></a>
<a href="https://codeclimate.com/github/cybercog/laravel-youtrack-sdk"><img src="https://img.shields.io/codeclimate/github/cybercog/laravel-youtrack-sdk.svg?style=flat-square" alt="Code Climate"></a>
<a href="https://github.com/cybercog/laravel-youtrack-sdk/releases"><img src="https://img.shields.io/github/release/cybercog/laravel-youtrack-sdk.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/laravel-youtrack-sdk/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/laravel-youtrack-sdk.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Laravel wrapper for the [YouTrack PHP SDK](https://github.com/cybercog/youtrack-php-sdk#readme) library provides set of tools to interact with [JetBrains YouTrack Issue Tracking and Project Management software](https://www.jetbrains.com/youtrack/). 

## Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
    - [YouTrack URL](#youtrack-url)
    - [Authorization methods](#authorization-methods)
- [Usage](#usage)
    - [Initialize API client](#initialize-api-client)
    - [API requests](#api-requests)
    - [API responses](#api-responses)
- [Change log](#change-log)
- [Contributing](#contributing)
- [Testing](#testing)
- [Security](#security)
- [Credits](#credits)
- [Alternatives](#alternatives)
- [License](#license)
- [About CyberCog](#about-cybercog)

## Features

- Using contracts to keep high customization capabilities.
- Multiple authorization strategies: Token, Cookie.
- Following PHP Standard Recommendations:
  - [PSR-1 (Basic Coding Standard)](http://www.php-fig.org/psr/psr-1/).
  - [PSR-2 (Coding Style Guide)](http://www.php-fig.org/psr/psr-2/).
  - [PSR-4 (Autoloading Standard)](http://www.php-fig.org/psr/psr-4/).
  - [PSR-7 (HTTP Message Interface)](http://www.php-fig.org/psr/psr-7/).
- Covered with unit tests.

## Requirements

- YouTrack >= 3.0 with REST-API enabled (always enabled, by default)
- PHP >= 7.1
- Guzzle HTTP Client >= 6.2
- Laravel >= 5.1.20

## Installation

The preferred method is via [composer](https://getcomposer.org). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
$ composer require cybercog/laravel-youtrack-sdk
```

If you are using Laravel 5.4 or lower - include the service provider within `app/config/app.php`:

```php
'providers' => [
    Cog\Laravel\YouTrack\Providers\YouTrackServiceProvider::class,
],
```

## Configuration

Laravel YouTrack SDK designed to work with default config, but it always could be modified. First of all publish it:

```bash
php artisan vendor:publish --provider="Cog\Laravel\YouTrack\Providers\YouTrackServiceProvider" --tag="config"
```

This will create a `config/youtrack.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

### YouTrack URL

YouTrack instance location could be defined in `.env` file:

```
YOUTRACK_BASE_URI=https://youtrack.custom.domain
```

### Authorization methods

Starting with YouTrack 2017.1 release [authorization based on permanent tokens](https://www.jetbrains.com/help/youtrack/standalone/2017.2/Manage-Permanent-Token.html) is recommended as the main approach for the authorization in your REST API calls.

By default Token authorization will be used. You could redefine it in `.env` file:

#### Token authorization

```
YOUTRACK_AUTH=token
YOUTRACK_TOKEN=your-permanents-token
```

#### Cookie authorization

```
YOUTRACK_AUTH=cookie
YOUTRACK_USERNAME=username
YOUTRACK_PASSWORD=secret
```

## Usage

### Initialize API client

```php
$youtrack = app(\Cog\YouTrack\Rest\YouTrackClient::class);
```

### API requests

#### HTTP request

```php
$method = 'POST'; // GET, POST, PUT, DELETE, PATCH or any custom ones
$response = $youtrack->request($method, '/issue', [
    'project' => 'TEST',
    'summary' => 'New test issue',
    'description' => 'Test description',
]);
```

You can [customize requests created and transferred by a client using request options](http://docs.guzzlephp.org/en/latest/request-options.html). Request options control various aspects of a request including, headers, query string parameters, timeout settings, the body of a request, and much more.

```php
$options = [
    'debug' => true,
    'sink' => '/path/to/dump/file',
];
$response = $youtrack->request('POST', '/issue', [
    'project' => 'TEST',
    'summary' => 'New test issue',
    'description' => 'Test description',
], $options);
```

#### HTTP GET request

```php
$response = $youtrack->get('/issue/TEST-1');
```

#### HTTP POST request

```php
$response = $youtrack->post('/issue', [
    'project' => 'TEST',
    'summary' => 'New test issue',
    'description' => 'Test description',
]);
```

#### HTTP PUT request

```php
$response = $youtrack->put('/issue/TEST-1', [
    'summary' => 'Updated summary',
    'description' => 'Updated description',
]);
```

#### HTTP DELETE request

```php
$response = $youtrack->delete('/issue/TEST-1');
```

### API responses

Each successful request to the API returns instance of `\Cog\YouTrack\Rest\Response\Contracts\Response` contract. By default it's `\Cog\YouTrack\Rest\Response\YouTrackResponse` class.

#### Get PSR HTTP response

PSR HTTP response could be accessed by calling `httpResponse` method on API Response.

```php
$youtrackResponse = $youtrack->get('/issue/TEST-1');
$psrResponse = $youtrackResponse->httpResponse();
```

#### Get response Cookie

Returns `Set-Cookie` headers as string from the HTTP response.

```php
$apiResponse = $youtrack->get('/issue/TEST-1');
$cookieString = $apiResponse->cookie();
```

#### Get response Location

Returns `Location` header from the HTTP response.

```php
$apiResponse = $youtrack->get('/issue/TEST-1');
$location = $apiResponse->location();
```

#### Transform response to array

```php
$apiResponse = $youtrack->get('/issue/TEST-1');
$location = $apiResponse->toArray();
```

#### Get HTTP response status code

```php
$apiResponse = $youtrack->get('/issue/TEST-1');
$location = $apiResponse->statusCode();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Testing

Run the tests with:

```sh
$ composer test
```

## Security

If you discover any security related issues, please email oss@cybercog.su instead of using the issue tracker.

## Credits

|  | @mention |
|---|---|
| ![@a-komarev](https://avatars2.githubusercontent.com/u/1849174?s=64) | [@a-komarev](https://github.com/a-komarev) |

[Laravel YouTrack SDK contributors list](../../contributors)

## Alternatives

Alternatives not found.

*Feel free to add more alternatives as Pull Request.*

## License

- `Laravel YouTrack SDK` package is open-sourced software licensed under the [MIT License](LICENSE).

## About CyberCog

[CyberCog](http://www.cybercog.ru) is a Social Unity of enthusiasts. Research best solutions in product & software development is our passion.

<a href="http://cybercog.ru"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>
