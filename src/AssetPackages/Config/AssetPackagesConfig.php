<?php

namespace AssetPackages\Config;

use Zend\Stdlib\AbstractOptions;

class AssetPackagesConfig extends AbstractOptions
{
    /**
     * @var array
     */
    protected $__packageConfigs__;

    /**
     * @var array
     */
    protected $__packageNames__;

    /**
     * @var array
     */
    protected $packages;

    /**
     * @var string
     */
    protected $scriptsPlacement = PackageConfig::INLINE;

    /**
     * @param string $scriptsPlacement
     */
    public function setScriptsPlacement($scriptsPlacement)
    {
        $this->scriptsPlacement = $scriptsPlacement;
    }

    /**
     * @return string
     */
    public function getScriptsPlacement()
    {
        return $this->scriptsPlacement;
    }

    /**
     * @param array $packages
     */
    public function setPackages($packages)
    {
        $this->packages = $packages;
    }

    /**
     * @return array
     */
    public function getPackages()
    {
        return $this->packages;
    }


    /**
     * @return array
     */
    public function getPackageNames()
    {
        if ($this->__packageNames__) {
            return $this->__packageNames__;
        }

        if (is_array($this->packages) && !empty($this->packages)) {
            $this->__packageNames__ = array_keys($this->packages);
        }

        if (empty($this->__packageNames__)) {
            $this->__packageNames__ = array();
        }

        return $this->__packageNames__;
    }

    public function isPackageConfigured($packageName)
    {
        return (is_int($packageName) || is_string($packageName)) && is_array($this->packages) && isset($this->packages[$packageName]);
   }

    /**
     * @param $packageName
     * @return PackageConfig
     * @throws \InvalidArgumentException
     */
    public function getPackageConfiguration($packageName)
    {
        if (!$this->isPackageConfigured($packageName))
        {
            $message = sprintf('Requested asset package with name: "%s" is not configured! Currently configured package names are: %s', $packageName, implode(', ', $this->getPackageNames()));
            throw new \InvalidArgumentException($message);
        }

        if (isset($this->__packageConfigs__[$packageName]))
        {
            return $this->__packageConfigs__[$packageName];
        }

        // if not configured inject default script placement
        if (!isset($this->packages[$packageName]['scripts_placement']))
        {
            $this->packages[$packageName]['scripts_placement'] = $this->scriptsPlacement;
        }

        $packageConfig = new PackageConfig($this->packages[$packageName]);
        $this->__packageConfigs__[$packageName] = $packageConfig;
        return $packageConfig;
    }
}