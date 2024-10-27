<?php

namespace Aligent\Chat\Api;

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
     * @param array $liveChatFormData The data to be set in the live chat configuration.
     *
     * @return void
     */
    public function setLiveChatConfigurationsFormData(array $liveChatFormData): void;

    /**
     * Set the license information for the live chat feature.
     *
     * @param string $licenseNumber
     */
    public function setLiveChatLicense(string $licenseNumber);

    /**
     * Set the group for live chat.
     *
     * @param string $groups
     */
    public function setLiveChatGroup(string $groups);

    /**
     * Set the parameters for the live chat feature.
     *
     * @param string $params
     */
    public function setLiveChatParams(string $params);

    /**
     * Retrieves the license key for the live chat service.
     *
     * @return string The license key for the live chat.
     */
    public function getLiveChatLicense(): string;

    /**
     * Retrieves the group identifier for the live chat service.
     *
     * @return string The group identifier for the live chat.
     */
    public function getLiveChatGroup(): string;

    /**
     * Retrieves the parameters for the live chat service.
     *
     * @return string The parameters for the live chat.
     */
    public function getLiveChatParams(): string;

    /**
     * Perform cache cleaning operation based on specified tags.
     *
     * @return void
     */
    public function cleanConfigCache(): void;

}
