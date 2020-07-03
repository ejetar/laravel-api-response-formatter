# API Response Formatter

## Table of Contents
- [About](#about)
- [How it works](#how-it-works)
  * [Example 1](#example-1)
    + [Output](#output)
  * [Example 2](#example-2)
    + [Output](#output-1)
  * [Example 3](#example-3)
    + [Output](#output-2)
  * [Example 4](#example-4)
    + [Output](#output-3)
- [Installation](#installation)
- [Get started](#get-started)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [License](#license)

## About
A simple and fast 🚀 library that displays responses in various formats, according to the Accept header, entered by the user. The library currently displays responses in JSON, XML, CSV, and YAML.

## How it works
The API Response Formatter package provides the developer with a middleware called api-response-formatter. This middleware takes Response and converts it to a certain format based on the user's request.

In practical terms:
1. Reads the contents of the Accept header to know which response format to display to the user.
2. Takes the original response content provided by Laravel and convert it to the desired format;
3. Displays the response to the user;

It also provides a custom ExceptionHandler, which allows exceptions to also be thrown in the formats you want. A very nice trick of this handler is that when the route is /api/*, it forces the response to be in JSON, CSV, YAML or XML (if no type is informed via Accept, then JSON is selected by default). This prevents the API, at some point of failure, returns an HTML error.

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
        "name":"Guilherme",
        "surname": "Girardi",
        "email": "guilhermeagirardi@gmail.com",
        "created_at": "2019-04-24 20:34:03",
        "updated_at": "2019-04-24 20:34:03"
    }
]
```
### Example 3
```
GET /api/v1/users
Accept: text/csv
```
#### Output
```csv
id,name,surname,email,created_at,updated_at
30,Guilherme,Girardi,guilhermeagirardi@gmail.com,"2019-04-24 20:34:03","2019-04-24 20:34:03"
```
### Example 4
```
GET /api/v1/users
Accept: application/x-yaml
```
#### Output
```yaml
id: 30
name: Guilherme
surname: Girardi
email: guilhermeagirardi@gmail.com
created_at: '2019-04-24 20:34:03'
updated_at: '2019-04-24 20:34:03'
```

## Installation
1. First, run: `composer require ejetar/api-response-formatter`;
2. Add `Ejetar\ApiResponseFormatter\Providers\AppServiceProvider::class` in `providers` array in `config/app.php` file;
3. In `app/Exceptions/Handler.php`, replace `Illuminate\Foundation\Exceptions\Handler` with `Ejetar\ApiResponseFormatter\Exceptions\Handler`;
4. In `public/index.php`, replace `Illuminate\Http\Request::capture()` with `Ejetar\ApiResponseFormatter\Requests\Request::capture()`;

## Get started
To start using this package is very simple, just call Middlware `api-response-formatter` in your `routes/api.php` file.

As in the following example:
```php
Route::middleware(['cors', 'api-response-formatter'])->prefix('/v1')->name('api.')->group(function() {
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
