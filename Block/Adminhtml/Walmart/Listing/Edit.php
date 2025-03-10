<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Block\Adminhtml\Walmart\Listing;

class Edit extends \Ess\M2ePro\Block\Adminhtml\Magento\Form\AbstractContainer
{
    /** @var \Ess\M2ePro\Model\Listing */
    protected $listing;

    //########################################

    public function _construct()
    {
        parent::_construct();

        // Initialization block
        // ---------------------------------------
        $this->setId('walmartListingEdit');
        $this->_controller = 'adminhtml_walmart_listing';
        $this->_mode = 'edit';
        // ---------------------------------------

        $this->listing = $this->getHelper('Data\GlobalData')->getValue('edit_listing');

        // Set buttons actions
        // ---------------------------------------
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->removeButton('delete');
        $this->removeButton('add');
        $this->removeButton('save');
        $this->removeButton('edit');
        // ---------------------------------------

        if (!is_null($this->getRequest()->getParam('back'))) {
            // ---------------------------------------
            $url = $this->getHelper('Data')->getBackUrl(
                '*/walmart_listing/index'
            );
            $this->addButton('back', array(
                'label'     => $this->__('Back'),
                'onclick'   => 'WalmartListingSettingsObj.backClick(\''.$url.'\')',
                'class'     => 'back'
            ));
            // ---------------------------------------
        }

        // ---------------------------------------
        $this->addButton('auto_action', array(
            'label'     => $this->__('Auto Add/Remove Rules'),
            'onclick'   => 'ListingAutoActionObj.loadAutoActionHtml();',
            'class'     => 'action-primary'
        ));
        // ---------------------------------------

        $backUrl = $this->getHelper('Data')->getBackUrlParam('list');

        // ---------------------------------------
        $url = $this->getUrl(
            '*/walmart_listing/save',
            array(
                'id'    => $this->listing['id'],
                'back'  => $backUrl
            )
        );
        $saveButtonsProps = ['save' => [
            'label'     => $this->__('Save And Back'),
            'onclick'   => 'WalmartListingSettingsObj.saveClick(\'' . $url . '\')',
            'class'     => 'save primary'
        ]];
        // ---------------------------------------

        $editBackUrl = $this->getHelper('Data')->makeBackUrlParam(
            $this->getUrl(
                '*/walmart_listing/edit',
                array(
                    'id' => $this->listing['id'],
                    'back'  => $backUrl
                )
            )
        );
        $url = $this->getUrl(
            '*/walmart_listing/save',
            array(
                'id'    => $this->listing['id'],
                'back'  => $editBackUrl
            )
        );
        // ---------------------------------------
        $saveButtons = [
            'id' => 'save_and_continue',
            'label' => $this->__('Save And Continue Edit'),
            'class' => 'add',
            'button_class' => '',
            'onclick'   => 'WalmartListingSettingsObj.saveAndEditClick(\''.$url.'\', 1)',
            'class_name' => 'Ess\M2ePro\Block\Adminhtml\Magento\Button\SplitButton',
            'options' => $saveButtonsProps
        ];

        $this->addButton('save_buttons', $saveButtons);

        // ---------------------------------------
    }

    //########################################

    protected function _prepareLayout()
    {
        $this->css->addFile('listing/autoAction.css');

        return parent::_prepareLayout();
    }

    //########################################

    public function getFormHtml()
    {
        $viewHeaderBlock = $this->createBlock('Listing\View\Header','', [
            'data' => ['listing' => $this->listing]
        ]);

        $this->jsUrl->addUrls($this->getHelper('Data')->getControllerActions(
            'Walmart\Listing\AutoAction',
            ['id' => $this->getRequest()->getParam('id')]
        ));

        $path = 'walmart_listing_autoAction/getCategoryTemplatesList';
        $this->jsUrl->add($this->getUrl('*/' . $path, [
            'marketplace_id' => $this->listing->getMarketplaceId(),
            'is_new_asin_accepted' => 1
        ]), $path);

        $this->jsTranslator->addTranslations([
            'Remove Category' => $this->__('Remove Category'),
            'Add New Group' => $this->__('Add New Group'),
            'Add/Edit Categories Rule' => $this->__('Add/Edit Categories Rule'),
            'Auto Add/Remove Rules' => $this->__('Auto Add/Remove Rules'),
            'Based on Magento Categories' => $this->__('Based on Magento Categories'),
            'You must select at least 1 Category.' => $this->__('You must select at least 1 Category.'),
            'Rule with the same Title already exists.' => $this->__('Rule with the same Title already exists.')
        ]);

        $this->jsPhp->addConstants(
            $this->getHelper('Data')->getClassConstants('\Ess\M2ePro\Model\Listing')
        );

        $this->js->addOnReadyJs(
<<<JS
    require([
        'M2ePro/Walmart/Listing/AutoAction'
    ], function(){

        window.ListingAutoActionObj = new WalmartListingAutoAction();

    });
JS
    );

        return $viewHeaderBlock->toHtml() . parent::getFormHtml();
    }

    //########################################
}