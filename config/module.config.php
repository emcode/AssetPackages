<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'AssetPackages\Config\AssetPackagesConfig' => 'AssetPackages\Config\AssetPackagesConfigFactory',
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'assetPackage' => 'AssetPackages\View\Helper\AssetPackageFactory'
        )
    ),
);
