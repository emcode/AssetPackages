<?php

namespace AssetPackages\Config;

use Zend\Stdlib\AbstractOptions;

class PackageConfig extends AbstractOptions
{
    const INLINE = "inline";
    const HEAD = "head";

    /**
     * @var array
     */
    protected $styles;

    /**
     * @var array
     */
    protected $scripts;

    /**
     *
     * @var string
     */
    protected $scriptsPlacement;

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
     * @param array $scripts
     */
    public function setScripts($scripts)
    {
        if (is_string($scripts))
        {
            $scripts = array($scripts);
        }

        $this->scripts = $scripts;
    }

    /**
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    public function getHeadScripts()
    {
        if (is_null($this->scripts))
        {
            return null;
        }

        if (isset($this->scripts[self::HEAD]))
        {
            return $this->scripts[self::HEAD];
        }

        if ($this->scriptsPlacement == self::HEAD && !isset($this->scripts[self::INLINE]))
        {
            return $this->scripts;
        }

        return null;
    }

    public function getInlineScripts()
    {
        if (is_null($this->scripts))
        {
            return null;
        }

        if (isset($this->scripts[self::INLINE]))
        {
            return $this->scripts[self::INLINE];
        }

        if ($this->scriptsPlacement == self::INLINE && !isset($this->scripts[self::HEAD]))
        {
            return $this->scripts;
        }

        return null;
    }


    /**
     * @param array $styles
     */
    public function setStyles($styles)
    {
        if (is_string($styles))
        {
            $styles = array($styles);
        }

        $this->styles = $styles;
    }

    /**
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

}