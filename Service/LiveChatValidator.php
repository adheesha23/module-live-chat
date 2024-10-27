<?php


namespace Aligent\Chat\Service;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class LiveChatValidator
 *
 * This class is responsible for validating live chat data, ensuring that required fields
 * are not empty before processing.
 */
class LiveChatValidator
{
    /**
     * Validates the live chat data.
     *
     * @param array $data
     * @throws LocalizedException
     */
    public function validate(array $data): void
    {
        $this->validateNotEmpty($data['livechat_license_number'] ?? '', 'Livechat License Number');
        $this->validateNotEmpty($data['livechat_groups'] ?: 0, 'Livechat Groups');
        $this->validateNotEmpty($data['livechat_params'] ?? '', 'Livechat Params');
    }

    /**
     * Checks if a field is not empty.
     *
     * @param string $value
     * @param string $fieldName
     * @throws LocalizedException
     */
    private function validateNotEmpty(string $value, string $fieldName): void
    {
        if (trim($value) === '') {
            throw new LocalizedException(__("Please Enter the $fieldName and try again."));
        }
    }
}
