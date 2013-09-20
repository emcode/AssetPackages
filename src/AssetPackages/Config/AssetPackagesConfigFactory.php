<?php

namespace AssetPackages\Config;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use AssetPackages\Config\AssetPackagesConfig;

class AssetPackagesConfigFactory implements FactoryInterface
{
    protected $configurationKey = 'asset_packages';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $configData = isset($config[$this->configurationKey]) ? $config[$this->configurationKey] : array();
        $AssetPackagesConfig = new AssetPackagesConfig($configData);
        return $AssetPackagesConfig;
    }
}

