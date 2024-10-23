<?php
namespace Aligent\Block\Adminhtml;

use Magento\Backend\Block\Widget\Form\Container;

/**
 * Edit class that extends the Container class for the adminhtml edit block.
 *
 */
class Edit extends Container
{
    protected function _construct(): void
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Aligent_Chat';
        $this->_controller = 'adminhtml_edit';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save'));

        $this->buttonList->remove('delete');
    }
}
