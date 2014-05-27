<?php

namespace AssetPackages\Resolver;

use Assetic\Asset\FileAsset;
use Assetic\Asset\HttpAsset;
use AssetManager\Resolver\AggregateResolverAwareInterface;
use AssetManager\Resolver\ResolverInterface;
use AssetPackages\Config\AssetPackagesConfig;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\AssetInterface;
use AssetManager\Exception;
use AssetManager\Service\AssetFilterManagerAwareInterface;
use AssetManager\Service\AssetFilterManager;

/**
 * This resolver allows the resolving of collections.
 * Collections are strictly checked by mime-type,
 * and added to an AssetCollection when all checks passed.
 */
class GroupResolver implements
    ResolverInterface,
    AggregateResolverAwareInterface,
    AssetFilterManagerAwareInterface
{

    /**
     * Map for multiple extensions per mime-type, setting a main extension for the mime types.
     *
     * @var array
     */
    protected $mimeTypes = array(
        'css'       => 'text/css',
        'js'        => 'application/javascript',
    );

    /**
     * @var ResolverInterface
     */
    protected $aggregateResolver;

    /**
     * @var AssetFilterManager The filterManager service.
     */
    protected $filterManager;

    /**
     * @var array the collections
     */
    protected $scriptCollections;

    /**
     * @var array the collections
     */
    protected $styleCollections;

    /**
     * @var AssetPackagesConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $publicPath;

    /**
     * Constructor
     *
     * Instantiate and optionally populate collections.
     *
     * @param array|Traversable $collections
     */
    public function __construct(\AssetPackages\Config\AssetPackagesConfig $config, $publicPath = null)
    {
        $this->publicPath = $publicPath;
        $this->config = $config;
        $this->scriptCollections = $config->getScriptCompressionGroups();
        $this->styleCollections = $config->getStyleCompressionGroups();
    }

    /**
     * Set the aggregate resolver.
     *
     * @param ResolverInterface $aggregateResolver
     */
    public function setAggregateResolver(ResolverInterface $aggregateResolver)
    {
        $this->aggregateResolver = $aggregateResolver;
    }

    /**
     * Get the aggregate resolver.
     *
     * @return ResolverInterface
     */
    public function getAggregateResolver()
    {
        return $this->aggregateResolver;
    }


    /**
     * {@inheritDoc}
     */
    public function resolve($name)
    {
        $nameWithSlash = '/' . $name;
        $styleGroup = false;
        $scriptGroup = false;

        if (isset($this->scriptCollections[$name]))
        {
            $collectionParts = $this->scriptCollections[$name];
            $scriptGroup = true;

        } else if (isset($this->scriptCollections[$nameWithSlash]))
        {
            $collectionParts = $this->scriptCollections[$nameWithSlash];
            $scriptGroup = true;

        } else if (isset($this->styleCollections[$name]))
        {
            $collectionParts = $this->styleCollections[$name];
            $styleGroup = true;

        } else if (isset($this->styleCollections[$nameWithSlash]))
        {
            $collectionParts = $this->styleCollections[$nameWithSlash];
            $styleGroup = true;

        } else {

            return null;
        }

        $collection = new AssetCollection();
        $mimeType   = null;
        $collection->setTargetPath($name);

        foreach ($collectionParts as $packageName) {

            if (!is_string($packageName)) {
                throw new Exception\RuntimeException(
                    'Asset package name should be of type string. got ' . gettype($asset)
                );
            }

            $packageConfig = $this->config->getPackageConfiguration($packageName);

            if ($styleGroup)
            {
                $files = $packageConfig->getStyles();
                $mimeType = $this->mimeTypes['css'];

            } else
            {
                $files = $packageConfig->getScripts();
                $mimeType = $this->mimeTypes['js'];
            }

            if (null === $files)
            {
                continue;
            }

            foreach($files as $file)
            {
                if (false === filter_var($file, FILTER_VALIDATE_URL))
                {
                    if (null !== $this->publicPath)
                    {
                        $file = $this->publicPath . '/' . ltrim($file, '/');
                    }

                    $asset = new FileAsset($file);

                } else
                {
                    $asset = new HttpAsset($file);
                }

                $asset->mimetype = $mimeType;
                $this->getAssetFilterManager()->setFilters($file, $asset);
                $collection->add($asset);
            }
        }

        $collection->mimetype = $mimeType;
        return $collection;
    }

    /**
     * Set the AssetFilterManager.
     *
     * @param AssetFilterManager $filterManager
     */
    public function setAssetFilterManager(AssetFilterManager $filterManager)
    {
        $this->filterManager = $filterManager;
    }

    /**
     * Get the AssetFilterManager
     *
     * @return AssetFilterManager
     */
    public function getAssetFilterManager()
    {
        return $this->filterManager;
    }
}
