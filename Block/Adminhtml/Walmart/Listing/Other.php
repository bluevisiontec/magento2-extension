<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Block\Adminhtml\Walmart\Listing;

class Other extends \Ess\M2ePro\Block\Adminhtml\Magento\Grid\AbstractContainer
{
    //########################################

    public function _construct()
    {
        parent::_construct();

        $this->setId('walmartListingOther');
        $this->_controller = 'adminhtml_walmart_listing_other';

        $this->isAjax = $this->getHelper('Data')->jsonEncode($this->getRequest()->isXmlHttpRequest());

        // Set buttons actions
        // ---------------------------------------
        $this->removeButton('add');
        // ---------------------------------------
    }

    protected function _prepareLayout()
    {
        $this->appendHelpBlock([
            'content' => $this->__(
                <<<HTML
    On this page, you can review the 3rd Party Listings imported by M2E Pro from your
    Channel Account associated with particular Marketplace.<br><br>

    In the grid below, click the 3rd Party Listing line to manage the Items.
    You can Map the 3rd Party Items to the related Magento Products and Move them to the selected M2E Pro Listing.
    Manage each Item individually or use the Mass Actions to update the Items in bulk.

HTML
            )
        ]);

        return parent::_prepareLayout();
    }

    //########################################
}