<?php

namespace AssetPackages\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use AssetPackages\Config\AssetPackagesConfig;
use AssetPackages\View\Helper\AssetPackages;

class AssetPackageFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mainServiceLocator = $serviceLocator->getServiceLocator();
        $config = $mainServiceLocator->get('AssetPackages\Config\AssetPackagesConfig');
        $viewHelper = new AssetPackage($config);
        return $viewHelper;
    }
}

