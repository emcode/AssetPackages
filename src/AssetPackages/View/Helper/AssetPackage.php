<?php

namespace AssetPackages\View\Helper;

use AssetPackages\Config\AssetPackagesConfig;
use AssetPackages\Config\PackageConfig;
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
     * @var bool
     */
    protected $isGroupingEnabled;

    /**
     * Constructor
     */
    public function __construct(AssetPackagesConfig $config)
    {
        $this->config = $config;
        $this->isGroupingEnabled = $config->areCompressionGroupsEnabled();
    }

    public function append($packageName)
    {
        $packageConfig = $this->config->getPackageConfiguration($packageName);

        if ($packageConfig->hasAnyStyles())
        {
            $styleGroup = $this->resolveStyleGroup($packageConfig, $packageName);

            if (null !== $styleGroup)
            {
                $this->getView()->headLink()->appendStylesheet($styleGroup);

            } else
            {
                $styles = $packageConfig->getStyles();

                foreach($styles as $stylePath)
                {
                    $this->getView()->headLink()->appendStylesheet($stylePath);
                }
            }
        }

        if (!$packageConfig->hasAnyScripts())
        {
            return $this;
        }

        $scriptGroup = $this->resolveScriptGroup($packageConfig, $packageName);

        if (null !== $scriptGroup)
        {
            $this->getView()->inlineScript()->appendFile($scriptGroup);
            return $this;
        }


        $headScripts = $packageConfig->getHeadScripts();
        $inlineScripts = $packageConfig->getInlineScripts();
        $haveScriptGroup = false;

        if ($headScripts)
        {
            foreach($headScripts as $scriptPath)
            {
                $this->getView()->headScript()->appendFile($scriptPath);
            }
        }

        if ($inlineScripts)
        {
            foreach($inlineScripts as $scriptPath)
            {
                $this->getView()->inlineScript()->appendFile($scriptPath);
            }
        }

        return $this;
    }

    protected function resolveStyleGroup(PackageConfig $packageConfig, $packageName)
    {
        $result = null;

        if ($this->isGroupingEnabled && $packageConfig->hasAnyStyles())
        {
            $groupName = $this->config->getStyleCompressionGroupForPackage($packageName);

            if (null !== $groupName)
            {
                $result = $groupName;
            }
        }

        return $result;
    }

    protected function resolveScriptGroup(PackageConfig $packageConfig, $packageName)
    {
        $result = null;

        if ($this->isGroupingEnabled && $packageConfig->hasAnyScripts())
        {
            $groupName = $this->config->getScriptCompressionGroupForPackage($packageName);

            if (null !== $groupName)
            {
                $result = $groupName;
            }
        }

        return $result;
    }


    public function prepend($packageName)
    {
        $packageConfig = $this->config->getPackageConfiguration($packageName);

        if ($packageConfig->hasAnyStyles())
        {
            $styleGroup = $this->resolveStyleGroup($packageConfig, $packageName);

            if (null !== $styleGroup)
            {
                $this->getView()->headLink()->prependStylesheet($styleGroup);

            } else
            {
                $styles = $packageConfig->getStyles();

                foreach($styles as $stylePath)
                {
                    $this->getView()->headLink()->prependStylesheet($stylePath);
                }
            }
        }

        if (!$packageConfig->hasAnyScripts())
        {
            return $this;
        }

        $scriptGroup = $this->resolveScriptGroup($packageConfig, $packageName);

        if (null !== $scriptGroup)
        {
            $this->getView()->inlineScript()->prependFile($scriptGroup);
            return $this;
        }


        $headScripts = $packageConfig->getHeadScripts();
        $inlineScripts = $packageConfig->getInlineScripts();
        $haveScriptGroup = false;

        if ($headScripts)
        {
            foreach($headScripts as $scriptPath)
            {
                $this->getView()->headScript()->prependFile($scriptPath);
            }
        }

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

