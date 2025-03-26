# Mailcow PHP API Client

## Getting Started

### Requirements

* [**PHP 8.3+**](https://www.php.net/downloads.php)
* Extensions: [Composer](https://getcomposer.org/), [PHP-JSON](https://www.php.net/manual/en/book.json.php)

In the root of your project execute the following:

```sh
$ composer require exbil/mailcow-php-api
```

or add this to your `composer.json` file:

```json
{
    "require": {
        "exbil/mailcow-php-api": "^0.14.2"
    }
}
```

Then perform the installation:

```sh
$ composer install --no-dev
```

## Usage

Search for the API Documentation [here](https://demo.mailcow.email/api/) or your own mailcow instance via `https://domain.com/api`.  
You need an API Key for that which can be found in an self-hosted instance within the admin dashboard.

### Example

```php
<?php
// Require the autoloader
require_once 'vendor/autoload.php';

// Use the library namespace
use Exbil\MailCowAPI;

// Then simply pass your API-Token when creating the API client object.
$client = new MailCowAPI('mailcow-with-https.example.com','MAILCOW_API_KEY');

// Then you are able to perform a request
var_dump($client->domains()->getDomains());
?>
```
