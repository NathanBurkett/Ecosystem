# RoadworkRah/Ecosystem

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

Ecosystem is a simple and smart environment manager for your user-facing scripts and stylesheets and provides basic constant creation and use for each Ecosystem.

Ecosystems are useful when you have collections of separate resources for differing sections of your application - http://example.com vs. http://example.com/admin. Creating an Ecosystem for each section allows to you manage each's resources in a collection one place while being able to add new resources to the collection on the fly.

## Install

To install Ecosystem as a Composer package, run:

``` bash
composer require roadworkrah/ecosystem
```

Once it's installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [

    ...

	RoadworkRah\Ecosystem\Providers\EcosystemServiceProvider::class,
]
```

And register the `Ecosystem` facade in the the `aliases` array:

```php
'aliases' => [

    ...

    'Ecosystem' => RoadworkRah\Ecosystem\Facades\Ecosystem::class,
```

Then publish Ecosystems's assets with `php artisan vendor:publish`. This will add the file `config/ecosystem.php`. This [config file][link-config] allows certain envionments to disable cache-busting strings.

## Usage

### Creating a New Ecosystem

Run `php artisan` from the console, and you'll see the new `make:ecosystem` command.

Running `php artisan make:ecosystem StandardEcosystem` from the console will create `StandardEcosystem` in `App\Library\Ecosystems` directory. If directory doesn't exist, the command will create it.

To place Ecosystem in a different directory, append `--namespace=Your\Namespace\Here` to the command. Doing so will create an Ecosystem in the string of directories starting with a lowercase version of the first directory segment - ie `--namespace=Resources\Ecosystems` would place the Ecosystem in `resources\Ecosystems`.

The command `php artisan make:ecosystem StandardEcosystem` would generate the following file in the `App\Library\Ecosystems` directory.

```php
<?php

namespace App\Library\Ecosystems;

use RoadworkRah\Ecosystem\Contracts\AssetCollectionContract;
use RoadworkRah\Ecosystem\Entities\AbstractEcosystem as Ecosystem;

class StandardEcosystem extends Ecosystem implements AssetCollectionContract
{
    /**
     * Default StandardEcosystem head scripts
     * @return array
     */
    final public function defaultHeadScripts()
    {
        return [
            '' => ['src' => '']
        ];
    }

    /**
    * Default StandardEcosystem stylesheets
    * @return array
    */
    final public function defaultStylesheets()
    {
        return [
            '' => ['href' => '']
        ];
    }

    /**
    * Default StandardEcosystem footer scripts
    * @return array
    */
    final public function defaultFooterScripts()
    {
        return [
            '' => ['src' => '']
        ];
    }
}

```

Adding assets to any of the `default` collections allows you to display them easily from any view.

```php
    final public function defaultHeadScripts()
    {
        return [
            'yahoo' => ['src' => '/yahoo.js'],
            'wahoo' => ['src' => '/wahoo.js'],
            'yippee' => ['src' => '/yippee.js']
        ];
    }
```
This will bundle the assets in a Laravel Collection until they're output in a view. In a view associated with this route
```php
    Ecosystem::getHeadScripts();
```

Would output
```html
<script src="/yahoo.js?123456789"></script>
<script src="/wahoo.js?123456789"></script>
<script src="/yippee.js?123456789"></script>
```

### Attaching to Routes

Ecosystems are assigned to routes and groups of routes by attaching them as middleware.

```php
Route::group(['middleware' => 'ecosystem', 'ecosystem' => 'App\Library\Ecosystems\StandardEcosystem'], function() {
    Route::get('/', 'IndexController@index');
});
```

Ecosystems are overridden so the most immediate one on the route is used

```php
Route::group(['middleware' => 'ecosystem', 'ecosystem' => 'App\Library\Ecosystems\StandardEcosystem'], function () {
     // NewStandardEcosystem would be used for the '/' route
    Route::get('/', ['middleware' => 'ecosystem', 'ecosystem' => 'App\Library\Ecosystems\NewStandardEcosystem', 'uses' => 'IndexController@index']);
});
```

### Dynamically Attaching Assets

Because the assets are not compiled and output until runtime, you have the ability to add one-offs to the Collection. This is typically done in a Controller.

```php
<?php

namespace App\Http\Controllers;

use Ecosystem;
// ...

class IndexController extends Controller
{
    public function index() {
        Ecosystem::addScriptToHead('blammo', ['src' => '/blammo.js']);
    }
}
```

Would change the `Ecosystem::getHeadScripts()` output to
```html
<script src="/yahoo.js?123456789"></script>
<script src="/wahoo.js?123456789"></script>
<script src="/yippee.js?123456789"></script>
<script src="/blammo.js?123456789"></script>
```

The `Ecosystem::addScriptToHead()`, `Ecosystem::addStylesheet()`, `Ecosystem::addScriptToFooter()` take a maximum of three arguments. A name for the asset, an array of attributes for the asset, and a option to set the asset before one already added to the Collection.
```php
function addScriptToHead($name, $attr = array(), $before = null)
```
Supplying the name of an already registered asset in the `$before` argument will push the new asset above the target.

```php
Ecosystem::addScriptToHead('blammo', ['src' => '/blammo.js'], 'yippee');
```
Yields
```html
<script src="/yahoo.js?123456789"></script>
<script src="/wahoo.js?123456789"></script>
<script src="/blammo.js?123456789"></script>
<script src="/yippee.js?123456789"></script>
```

## Available Methods

The `Ecosystem facade` exposes certain methods to interact with the current Ecosystem while the actions fall within a route which has an Ecosystem registered to it.

These three methods output any assets registered to its collection in html elements and are intended to be used in `views`:
```php
Ecosystem::getHeadScripts();
```

```php
Ecosystem::getStylesheets();
```

```php
Ecosystem::getFooterScripts();
```

`Ecosystem` lets you add one-off assets in any area that the route middleware attached Ecosystem covers. However, this is typically done in Controllers.

```php
<?php

namespace App\Http\Controllers;

// import the facade
use Ecosystem;

// ...

Ecosystem::addHeadScript($name, $attr = array(), $before = null);

Ecosystem::addStylesheet($name, $attr = array(), $before = null);

Ecosystem::addFooterScript($name, $attr = array(), $before = null);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ```nathan at nathanb dot me``` instead of using the issue tracker.

## Credits

- [Nathan Burkett][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/roadworkrah/ecosystem.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/thephpleague/:package_name/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/league/:package_name.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/roadworkrah/ecosystem
[link-travis]: https://travis-ci.org/roadworkrah/ecosystem
[link-downloads]: https://packagist.org/packages/roadworkrah/ecosystem
[link-author]: https://github.com/NathanBurkett
[link-contributors]: ../../contributors
[link-config]: src/config/ecosystem.php
