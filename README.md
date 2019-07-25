# API Response Formatter

## Table of Contents
- [About](#about)
- [How it works](#how-it-works)
  * [Example 1](#example-1)
    + [Output](#output)
  * [Example 2](#example-2)
    + [Output](#output-1)
- [Installation](#installation)
- [Get started](#get-started)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [License](#license)
- [Credits](#credits)

## About
A simple and fast 🚀 library that displays responses in various formats, according to the Accept header, entered by the user. The library currently displays responses in JSON, XML, CSV, and YAML.

## How it works
The API Response Formatter package provides the developer with a middleware called response_formatter. This middleware takes Response and converts it to a certain format based on the user's request.

In practical terms:
1. Read the contents of the Accept header to know which response format to display to the user.
2. Take the original response content provided by Laravel and convert it to the desired format;
3. Display the response to the user;

### Example 1
```
GET /api/v1/users
Accept: application/xml
```
#### Output
```xml
<?xml version="1.0" encoding="utf-8"?>
<xml>
    <item>
        <id>30</id>
        <name>Guilherme</name>
        <surname>Girardi</surname>
        <email>guilhermeagirardi@gmail.com</email>
        <created_at>2019-04-24 20:34:03</created_at>
        <updated_at>2019-04-24 20:34:03</updated_at>
    </item>
    ...
</xml>
```
### Example 2
```
GET /api/v1/users
Accept: application/json
```
#### Output
```json
[
    {
        "id": 30,
        "name":"Acme",
        "surname": "Girardi",
        "email": "guilhermeagirardi@gmail.com",
        "created_at": "2019-04-24 20:34:03",
        "updated_at": "2019-04-24 20:34:03",
    }
]
```

## Installation
First, run: `composer require ejetar/api-response-formatter`

After, add `Ejetar\ApiResponseFormatter\App\Providers\AppServiceProvider::class` in `providers` array in `config/app.php` file.

## Get started
To start using this package is very simple, just call Middlware `response_formatter` in your `routes/api.php` file.

As in the following example:
```php
Route::middleware(['cors', 'response_formatter'])->prefix('/v1')->name('api.')->group(function() {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('me', 'UsersController@me')->name('me');
    });
});
```

## Changelog
Nothing for now...

## Contributing
Contribute to this wonderful project, it will be a pleasure to have you with us. Let's help the free software community. You are invited to incorporate new features, make corrections, report bugs, and any other form of support.
Don't forget to star in this repository! 😀 

## License
This library is a open-source software licensed under the MIT license.

## Credits
Special thanks to [SoapBox](https://github.com/SoapBox) for developing the [laravel-formatter](https://github.com/SoapBox/laravel-formatter) library as it plays a key role in our project.
