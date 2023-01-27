# reCAPTCHA PHP client library

reCAPTCHA is a free CAPTCHA service that protects websites from spam and abuse.
This is a PHP library that wraps up the server-side verification step required
to process responses from the reCAPTCHA service. This client supports both v2
and v3.

- reCAPTCHA: https://www.google.com/recaptcha
- This repo: https://github.com/lptech-asia/lp-recaptcha
- Version: 1.0.0
- License: BSD, see [LICENSE](LICENSE)

## Installation

### Composer (recommended)

Use [Composer](https://getcomposer.org) to install this library from Packagist:
[`lptech-asia/lp-recaptcha`](https://packagist.org/packages/lptech-asia/lp-recaptcha)

Run the following command from your project directory to add the dependency:

```sh
composer require lptech-asia/lp-recaptcha "master"
```

Alternatively, add the dependency directly to your `composer.json` file:

```json
"require": {
    "lptech-asia/lp-recaptcha": "master"
}
```

### Direct download

Download the [ZIP file](https://github.com/lptech-asia/lp-recaptcha/archive/master.zip)
and extract into your project. An autoloader script is provided in
`src/autoload.php` which you can require into your script. For example:

```php
require_once '/path/to/recaptcha.php';
$recaptcha = new LPRecaptcha('secret-key');
```

The classes in the project are structured according to the
[PSR-4](http://www.php-fig.org/psr/psr-4/) standard, so you can also use your
own autoloader or require the needed files directly in your code.

## Usage

First obtain the appropriate keys for the type of reCAPTCHA you wish to
integrate for v2 at https://www.google.com/recaptcha/admin or v3 at
https://g.co/recaptcha/v3.

Then follow the [integration guide on the developer
site](https://developers.google.com/recaptcha/intro) to add the reCAPTCHA
functionality into your frontend.

This library comes in when you need to verify the user's response. On the PHP
side you need the response from the reCAPTCHA service and secret key from your
credentials. Instantiate the `LPRecaptcha` class with your secret key, specify any
additional validation rules, and then call `verify()` with the reCAPTCHA
response (usually in `$_POST['g-recaptcha-response']` or the response from
`grecaptcha.execute()` in JS which is in `$gRecaptchaResponse` in the example)
and user's IP address. For example:

```php
<?php
$require '../recaptcha.php';
$recaptcha = new LPRecaptcha('secret-key');
$recaptcha->setSitekey('site-key');
if ($resp->isSuccess()) {
    // Verified!
} else {
    $errors = $resp->getErrorCodes();
}
```

The following methods are available:

- `setExpectedHostname($hostname)`: ensures the hostname matches. You must do
  this if you have disabled "Domain/Package Name Validation" for your
  credentials.
- `setExpectedApkPackageName($apkPackageName)`: if you're verifying a response
  from an Android app. Again, you must do this if you have disabled
  "Domain/Package Name Validation" for your credentials.
- `setExpectedAction($action)`: ensures the action matches for the v3 API.
- `setScoreThreshold($threshold)`: set a score threshold for responses from the
  v3 API
- `setChallengeTimeout($timeoutSeconds)`: set a timeout between the user passing
  the reCAPTCHA and your server processing it.

Each of the `set`\*`()` methods return the `LPRecaptcha` instance so you can chain
them together. For example:

```php
<?php
$recaptcha = new LPRecaptcha('secret-key');
$resp = $recaptcha->setExpectedHostname('recaptcha-demo.appspot.com')
                  ->setExpectedAction('homepage')
                  ->setScoreThreshold(0.5)
                  ->verify($gRecaptchaResponse);

if ($resp->isSuccess()) {
    // Verified!
} else {
    $errors = $resp->getErrorCodes();
}
```