<?php

namespace Grindnu\DigitalOceanSpaces\Driver;

use Magento\AwsS3\Driver\AwsS3Factory;
use Magento\Framework\Exception\LocalizedException;
use Magento\RemoteStorage\Driver\DriverException;
use Magento\RemoteStorage\Driver\RemoteDriverInterface;

/**
 * Creates a pre-configured instance of DigitalOcean S3 driver.
 */
class DigitalOceanS3Factory extends AwsS3Factory
{
    private const DIGITAL_OCEAN_SPACES_ENDPOINT = 'https://%s.digitaloceanspaces.com/';

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
                $this->config->getPrefix(),
                $this->config->getCacheAdapter(),
                $this->config->getCacheConfig()
            );
        } catch (LocalizedException $exception) {
            throw new DriverException(__($exception->getMessage()), $exception);
        }
    }
}
