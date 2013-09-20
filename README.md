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
After enabling **AssetPackages** module you can include whole libraries using new **assetPackage** view helper:
```php
<?php $this->assetPackage()->append('bootstrap')
                           ->append('custom-lib') ?>
```
Given right configuration, above code will include both CSS and JS files for selected libraries in right order. As with standard ZF2
 view helpers you can influence order of included assets using either **append** and **prepend** methods.
```php
<?php $this->assetPackage()->append('custom-lib')
                           ->prepend('jquery') ?>
```
You can decide wheather script tags should be added to head section or as inline script tags within body.
Internally **assetPackage** view helper works as wrapper for **headLink**, **headScript** and **inlineScript** helpers so you can use those
as well at the same time without any problems. At the end - probably in your layout - you should render tags in standard way:
```php
<?php echo $this->headLink() ?>
<?php echo $this->headScript() ?>
<?php echo $this->inlineScript() ?>
```

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
3. Next you can include whole libraries in your template or partial:

    ```php
    <?php // include CSS and JS files ?>
    <?php $this->assetPackage()->append('custom-lib')
    <?php // render tags in your layout ?>
    <?php echo $this->headLink() ?>
    <?php echo $this->inlineScript() ?>

    ```
Including scripts in head or within body
-----
There is also **scripts_placement** flag that lets you decide where scripts should be added. This flag receives
values: **inline** or **head** (default is: inline).
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
Using it with other modules
-----
From what I seen it this module works well with **AssetAliases** module enabled at the same time. I have to investigate
if it would be usefull to usid with **AssetManager** - but there shouldn't be any problems.
