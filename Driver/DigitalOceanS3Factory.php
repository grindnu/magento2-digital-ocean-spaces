<?php

namespace Grindnu\DigitalOceanSpaces\Driver;

use Magento\AwsS3\Driver\AwsS3Factory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\RemoteStorage\Driver\Adapter\Cache\CacheInterfaceFactory;
use Magento\RemoteStorage\Driver\Adapter\CachedAdapterInterfaceFactory;
use Magento\RemoteStorage\Driver\Adapter\MetadataProviderInterfaceFactory;
use Magento\RemoteStorage\Driver\DriverException;
use Magento\RemoteStorage\Driver\RemoteDriverInterface;
use Magento\RemoteStorage\Model\Config;

/**
 * Creates a pre-configured instance of AWS S3 driver.
 */
class DigitalOceanS3Factory extends AwsS3Factory
{
    private const DIGITAL_OCEAN_SPACES_ENDPOINT = 'https://%s.digitaloceanspaces.com/';

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Config $config
     * @param MetadataProviderInterfaceFactory $metadataProviderFactory
     * @param CacheInterfaceFactory $cacheInterfaceFactory
     * @param CachedAdapterInterfaceFactory $cachedAdapterInterfaceFactory
     * @param string|null $cachePrefix
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Config $config,
        MetadataProviderInterfaceFactory $metadataProviderFactory,
        CacheInterfaceFactory $cacheInterfaceFactory,
        CachedAdapterInterfaceFactory $cachedAdapterInterfaceFactory,
        string $cachePrefix = null
    ) {
        $this->config = $config;

        parent::__construct(
            $objectManager,
            $config,
            $metadataProviderFactory,
            $cacheInterfaceFactory,
            $cachedAdapterInterfaceFactory,
            $cachePrefix
        );
    }

    /**
     * @inheritDoc
     */
    public function create(): RemoteDriverInterface
    {
        try {
            // Override endpoint to allow DigitalOcean.
            $config = $this->config->getConfig();
            $config['endpoint'] = sprintf(self::DIGITAL_OCEAN_SPACES_ENDPOINT, $config['region']);

            return $this->createConfigured(
                $config,
                $this->config->getPrefix()
            );
        } catch (LocalizedException $exception) {
            throw new DriverException(__($exception->getMessage()), $exception);
        }
    }
}
