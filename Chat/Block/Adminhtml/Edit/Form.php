<?php
namespace Aligent\Chat\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Exception\LocalizedException;

class Form extends Generic
{
    /**
     * Prepares the form structure for submitting live chat details.
     *
     * @return Form
     * @throws LocalizedException
     */
    protected function _prepareForm(): Form
    {
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save'),
                    'method' => 'post',
                ]
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Submit Live Chat Details')]);

        $fieldset->addField(
            'livechat_license_number',
            'text',
            [
                'name' => 'livechat_license_number',
                'label' => __('License Number'),
                'title' => __('License Number'),
            ]
        );

        $fieldset->addField(
            'livechat_groups',
            'text',
            [
                'name' => 'livechat_groups',
                'label' => __('Groups'),
                'title' => __('Groups'),
            ]
        );

        $fieldset->addField(
            'livechat_params',
            'text',
            [
                'name' => 'livechat_params',
                'label' => __('Params'),
                'title' => __('Params'),
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
