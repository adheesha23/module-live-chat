<?php

namespace Aligent\Chat\Api\Data;

/**
 * Interface for managing the configuration settings of the Aligent LiveChat module.
 */
interface ConfigurationInterface
{
    /**
     * Check if the feature is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Set the live chat form data into the configurations.
     *
     * @param mixed $liveChatFormData The data to be set in the live chat configuration.
     *
     * @return void
     */
    public function updateLiveChatConfigurationsFormData(mixed $liveChatFormData): void;

    /**
     * Set the license information for the live chat feature.
     *
     * @param mixed $licenseNumber
     */
    public function updateLiveChatLicense(mixed $licenseNumber);

    /**
     * Set the group for live chat.
     *
     * @param mixed $groups
     */
    public function updateLiveChatGroup(mixed $groups);

    /**
     * Set the parameters for the live chat feature.
     *
     * @param mixed $params
     */
    public function updateLiveChatParams(mixed $params);

    /**
     * Perform cache cleaning operation based on specified tags.
     *
     * @return void
     */
    public function cacheCleanByTags(): void;

}
