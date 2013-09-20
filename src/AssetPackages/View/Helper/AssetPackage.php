<?php

namespace AssetPackages\View\Helper;

use AssetPackages\Config\AssetPackagesConfig;
use stdClass;
use Zend\View;
use Zend\View\Exception;
use Zend\View\Helper\Placeholder\Container\AbstractStandalone;

class AssetPackage extends AbstractStandalone
{
    /**
     * @var AssetPackagesConfig
     */
    protected $config;

    /**
     * Constructor
     */
    public function __construct(AssetPackagesConfig $config)
    {
        $this->config = $config;
    }

    public function append($packageName)
    {
        $packageConfig = $this->config->getPackageConfiguration($packageName);

        $styles = $packageConfig->getStyles();

        if ($styles)
        {
            foreach($styles as $stylePath)
            {
                $this->getView()->headLink()->appendStylesheet($stylePath);
            }
        }

        $headScripts = $packageConfig->getHeadScripts();

        if ($headScripts)
        {
            foreach($headScripts as $scriptPath)
            {
                $this->getView()->headScript()->appendFile($scriptPath);
            }
        }

        $inlineScripts = $packageConfig->getInlineScripts();

        if ($inlineScripts)
        {
            foreach($inlineScripts as $scriptPath)
            {
                $this->getView()->inlineScript()->appendFile($scriptPath);
            }
        }

        return $this;
    }

    public function prepend($packageName)
    {
        $packageConfig = $this->config->getPackageConfiguration($packageName);

        $styles = $packageConfig->getStyles();

        if ($styles)
        {
            foreach($styles as $stylePath)
            {
                $this->getView()->headLink()->prependStylesheet($stylePath);
            }
        }

        $headScripts = $packageConfig->getHeadScripts();

        if ($headScripts)
        {
            foreach($headScripts as $scriptPath)
            {
                $this->getView()->headScript()->prependFile($scriptPath);
            }
        }

        $inlineScripts = $packageConfig->getInlineScripts();

        if ($inlineScripts)
        {
            foreach($inlineScripts as $scriptPath)
            {
                $this->getView()->inlineScript()->prependFile($scriptPath);
            }
        }

        return $this;
    }

    /**
     * @param \AssetPackages\Config\AssetPackagesConfig $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return \AssetPackages\Config\AssetPackagesConfig
     */
    public function getConfig()
    {
        return $this->config;
    }


}

