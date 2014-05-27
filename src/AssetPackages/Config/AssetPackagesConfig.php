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
    protected $__styleCompressionGroups__;

    /**
     * @var array
     */
    protected $__scriptCompressionGroups__;

    /**
     * @var array
     */
    protected $packages;

    /**
     * @var string
     */
    protected $scriptsPlacement = PackageConfig::INLINE;

    /**
     * @var array
     */
    protected $compressionGroups;

    /**
     * @param array $compressionGroups
     */
    public function setCompressionGroups($compressionGroups)
    {
        $this->compressionGroups = $compressionGroups;
    }

    /**
     * @return array
     */
    public function getCompressionGroups()
    {
        return $this->compressionGroups;
    }

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

    public function areCompressionGroupsEnabled()
    {
        return (bool) (isset($this->compressionGroups['enabled']) && $this->compressionGroups['enabled']);
    }

    public function getStyleCompressionGroups()
    {
        if (null !== $this->__styleCompressionGroups__)
        {
            return $this->__styleCompressionGroups__;
        }

        if (isset($this->compressionGroups['styles']) && is_array($this->compressionGroups['styles']))
        {
            $this->__styleCompressionGroups__ = $this->compressionGroups['styles'];
            
        } else
        {
            $this->__styleCompressionGroups__ = false;
        }

        return $this->__styleCompressionGroups__;
    }

    public function getScriptCompressionGroups()
    {
        if (null !== $this->__scriptCompressionGroups__)
        {
            return $this->__scriptCompressionGroups__;
        }

        if (isset($this->compressionGroups['scripts']) && is_array($this->compressionGroups['scripts']))
        {
            $this->__scriptCompressionGroups__ = $this->compressionGroups['scripts'];

        } else
        {
            $this->__scriptCompressionGroups__ = false;
        }

        return $this->__scriptCompressionGroups__;
    }

    public function getStyleCompressionGroupForPackage($packageName)
    {
        $allGroups = $this->getStyleCompressionGroups();

        if (!$allGroups) return null;

        $result = null;

        foreach($allGroups as $groupName => $packagesInGroup)
        {
            if (in_array($packageName, $packagesInGroup))
            {
                $result = $groupName;
                break;
            }
        }

        return $result;
    }

    public function getScriptCompressionGroupForPackage($packageName)
    {
        $allGroups = $this->getScriptCompressionGroups();

        if (!$allGroups) return null;

        $result = null;

        foreach($allGroups as $groupName => $packagesInGroup)
        {
            if (in_array($packageName, $packagesInGroup))
            {
                $result = $groupName;
                break;
            }
        }

        return $result;
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