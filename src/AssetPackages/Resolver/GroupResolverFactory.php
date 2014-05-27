<?php

namespace AssetPackages\Resolver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use AssetPackages\Resolver\GroupResolver;

class GroupResolverFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return CollectionResolver
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $globalConfig = $serviceLocator->get('Config');
        $publicPath = null;

        if (isset($globalConfig['asset_manager']['resolver_configs']['public_path']))
        {
            $publicPath = $globalConfig['asset_manager']['resolver_configs']['public_path'];
        }

        $config = $serviceLocator->get('AssetPackages\Config\AssetPackagesConfig');
        $groupResolver = new GroupResolver($config, $publicPath);
        return $groupResolver;
    }
}
