AssetPackages
================
Version 0.0.1

Introduction
------------
AssetPackages ZF2 module represents a little bit more abstract approach to including CSS and JS in your templates.
Normally if you want to include bootstrap or some other custom library usually you need to include both CSS and JS files.
You might do this like that:
```php
<?php echo $this->headLink()
                ->appendStylesheet('/assets/some-module/bootstrap/css/bootstrap.css')
                ->appendStylesheet('/assets/custom-module/file-1.css')
                ->appendStylesheet('/assets/custom-module/file-2.css') ?>

<?php echo $this->inlineScript()
                ->appendFile('/assets/some-module/bootstrap/js/bootstrap.js')
                ->appendFile('/assets/custom-module/file-1.js')
                ->appendFile('/assets/custom-module/file-2.js')
                ->appendFile('/assets/custom-module/file-3.js') ?>
```
After enabling **AssetPackages** module you can include whole libraries using new `assetPackage` view helper:
```php
<?php $this->assetPackage()->append('bootstrap')
                           ->append('custom-lib') ?>
```
Given right configuration, above code will include both CSS and JS files for selected libraries in right order. As with standard ZF2
 view helpers you can influence order of included assets using either `append` and `prepend` methods.
```php
<?php $this->assetPackage()->append('custom-lib')
                           ->prepend('jquery') ?>
```
Using configuration you can decide wheather script tags should be included in head section or as inline script tags within body.
Internally `assetPackage` view helper works as wrapper for `headLink`, `headScript` and `inlineScript` helpers so you can use those
as well at the same time without any problems. At the end - probably in your layout - you should render tags in standard way:
```php
<?php echo $this->headLink() ?>
<?php echo $this->headScript() ?>
<?php echo $this->inlineScript() ?>
```

Installation
-----
If you are using composer you can install it by adding line to your composer.json

    
    "simplercode/asset-packages": "dev-master@dev"
 

Configuration
-----
1. Enable AssetPackages module in your application.config.php
2. In any config file you can configure yout libraries, for ex:

    ```php
    array(
        'asset_packages' => array(
            'packages' => array(
                'bootstrap' => array(
                    'styles' => '/assets/area42/vendor/bootstrap/css/bootstrap.min.css',
                    'scripts' =>'/assets/area42/vendor/bootstrap/js/bootstrap.min.js'
                ),
                'custom-lib' => array(
                    'styles' => array(
                        '/assets/custom-module/file-1.css',
                        '/assets/custom-module/file-2.css'
                    ),
                    'scripts' => array(
                        '/assets/custom-module/file-1.js',
                        '/assets/custom-module/file-2.js',
                        '/assets/custom-module/file-3.js'
                    ),
                ),
            ),
        ),
    );
    ```
3. Next you can include whole libraries in your template or partial using `assetPackage` helper:

    ```php
    <?php $this->assetPackage()->append('custom-lib')
    ```
    
4. In your layout remember to use native view helpers to actually render tags:
    
    ```php
    <?php echo $this->headLink() ?>
    <?php echo $this->inlineScript() ?>

    ```
5. Optionally if you don't like name of `assetPackage` view helper you can change it by configuring alias:
    ```php
    'view_helpers' => array(
        'aliases' => array(
            'asset' => 'assetPackage'
        ),
    ),
    ```
   Then you can use it like this:

    ```php
    <?php $this->asset()->append('jquery')
                        ->append('select2')
                        ->append('custom-lib')
    ```

Including scripts in head or within body
-----
There is also `scripts_placement` flag that lets you decide where scripts should be added. This flag receives
values: `inline` or `head` (default is: `inline`).
```php
array(
    'asset_packages' => array(
        'scripts_placement' => "head" // set value for all packages
        'packages' => array(
            'custom-library' => array(
                'scripts_placement' => "inline" // set value for all scripts within "custom-library" package
                'scripts' => ...
            )
        )
    ),
)
```
You can also assign some scripts to head and some to inline within one package using following syntax:
```php
array(
    'asset_packages' => array(
        'packages' => array(
            'custom-library' => array(
                'scripts' => array(
                    'head' => array(
                        'head-1.js',
                        'head-2.js'
                    ),
                    'inline' => array(
                        'inline-1.js',
                        'inline-2.js'
                    )
                )
            )
        )
    ),
)
```

Using it together with AssetManager
-----  
This module operates only in view layer. It's role is to map your asset's library name to **one** or **few** different
paths - and then include those paths using native ZF2 view helpers. Because of that there are no problems with using
it together with AssetManager module (which takes care of serving and caching aliases after receiving HTTP request).    
     
It's easy to load different `asset_packages` mapping configuration depending on your current environment [using 
method described here]. That way you have ability to load diffrent versions of your assets depending on your
configuration - without changing your templates at all.    
    
Its worth to mention that native view helpers take care of duplicate imports of scripts and styles so you don't
have to worry about importing same files twice.     
    
What is more AssetPackages module should also work fairly well with [AssetAliases] module too.   
   
[AssetAliases]: https://github.com/emcode/AssetAliases
[AssetManager]: https://github.com/RWOverdijk/AssetManager
[using method described here]: http://framework.zend.com/manual/2.2/en/tutorials/config.advanced.html
