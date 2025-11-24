# Mailcow PHP API Client

A full-featured PHP client for the MailCow API with support for all MailCow endpoints.

## Getting Started

### Requirements

* **PHP 7.4+** (PHP 8.0+ recommended)
* Extensions: [Composer](https://getcomposer.org/), [PHP-JSON](https://www.php.net/manual/en/book.json.php)
* [Guzzle HTTP Client](https://docs.guzzlephp.org/) 7.5+

### Installation

In the root of your project execute the following:

```sh
composer require exbil/mailcow-php-api
```

or add this to your `composer.json` file:

```json
{
    "require": {
        "exbil/mailcow-php-api": "^0.15.0"
    }
}
```

Then perform the installation:

```sh
composer install --no-dev
```

## Usage

Search for the API Documentation [here](https://demo.mailcow.email/api/) or your own mailcow instance via `https://domain.com/api`.  
You need an API Key which can be found in your self-hosted instance within the admin dashboard.

### Basic Example

```php
<?php
require_once 'vendor/autoload.php';

use Exbil\MailCowAPI;

// Create API client with default settings
$client = new MailCowAPI('https://mailcow.example.com', 'YOUR_API_KEY');

// Perform requests
$domains = $client->domains()->getDomains();
var_dump($domains);
```

### Advanced Configuration

#### SSL Verification

By default, SSL certificates are verified. For development environments with self-signed certificates:

```php
// Disable SSL verification (NOT recommended for production!)
$client = new MailCowAPI(
    'https://mailcow.local', 
    'YOUR_API_KEY',
    null,    // HTTP client (null = default)
    false    // Disable SSL verification
);
```

#### Custom Timeout

The default timeout is 120 seconds. You can customize it:

```php
// Set custom timeout (in seconds)
$client = new MailCowAPI(
    'https://mailcow.example.com',
    'YOUR_API_KEY',
    null,    // HTTP client
    true,    // Verify SSL
    60       // 60 seconds timeout
);
```

#### Custom HTTP Client

Inject your own Guzzle client for advanced configurations:

```php
use GuzzleHttp\Client;

$httpClient = new Client([
    'timeout' => 30,
    'proxy' => 'http://proxy.example.com:8080',
    // ... other Guzzle options
]);

$client = new MailCowAPI(
    'https://mailcow.example.com',
    'YOUR_API_KEY',
    $httpClient
);
```

### Environment-Based Configuration

```php
// Recommended: Use environment variables
$client = new MailCowAPI(
    getenv('MAILCOW_URL'),
    getenv('MAILCOW_API_KEY'),
    null,
    getenv('APP_ENV') === 'production'  // Only verify SSL in production
);
```

## Available API Endpoints

The client supports all MailCow API endpoints through dedicated handler classes:

```php
$client->domains()          // Domain management
$client->mailBoxes()        // Mailbox operations
$client->aliases()          // Alias management
$client->antiSpam()         // Anti-spam settings
$client->dkim()             // DKIM configuration
$client->fail2ban()         // Fail2Ban management
$client->quarantine()       // Quarantine operations
$client->logs()             // Log access
$client->status()           // System status
$client->queueManager()     // Mail queue management
$client->routing()          // Routing configuration
$client->oAuth()            // OAuth clients
$client->resources()        // Resource management
$client->appPasswords()     // App-specific passwords
$client->fwdhosts()         // Forward hosts
$client->ratelimits()       // Rate limiting
$client->domainAdmin()      // Domain admin management
$client->addressRewrite()   // Address rewriting rules
$client->tlsPolicy()        // TLS policy management
$client->SSO()              // Single Sign-On
$client->CORS()             // CORS configuration
$client->IdentityProvider() // Identity provider settings
```

### Example Operations

```php
// Get all domains
$domains = $client->domains()->getDomains();

// Create a new mailbox
$client->mailBoxes()->create([
    'local_part' => 'user',
    'domain' => 'example.com',
    'password' => 'securePassword123',
    'quota' => 2048
]);

// Get system status
$status = $client->status()->get();

// View logs
$logs = $client->logs()->get('mailcow', 100);
```

## Constructor Parameters

```php
new MailCowAPI(
    string $url,                    // Required: MailCow instance URL
    string $token,                  // Required: API token
    ?Client $httpClient = null,     // Optional: Custom Guzzle client
    bool $verifySSL = true,         // Optional: Verify SSL certificates
    int $timeout = 120              // Optional: Request timeout in seconds
);
```

## Security Best Practices

- ✅ **Always enable SSL verification in production** (`verifySSL = true`)
- ✅ Store API keys in environment variables, not in code
- ✅ Use HTTPS URLs for your MailCow instance
- ✅ Rotate API keys regularly
- ⚠️ Only disable SSL verification in trusted development environments

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL-3.0-or-later License.

## Links

- [MailCow Documentation](https://docs.mailcow.email/)
- [MailCow API Demo](https://demo.mailcow.email/api/)
- [GitHub Repository](https://github.com/Exbil/mailcow-php-api)
- [Packagist Package](https://packagist.org/packages/exbil/mailcow-php-api)