<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Walmart\Listing\AutoAction;

class GetAutoCategoryFormHtml extends \Ess\M2ePro\Controller\Adminhtml\Walmart\Listing\AutoAction
{
    //########################################

    public function execute()
    {
        // ---------------------------------------
        $listingId = $this->getRequest()->getParam('id');
        $listing = $this->walmartFactory->getCachedObjectLoaded('Listing', $listingId);
        $this->getHelper('Data\GlobalData')->setValue('walmart_listing', $listing);
        // ---------------------------------------

        $block = $this->createBlock('Walmart\Listing\AutoAction\Mode\Category\Form');

        $this->setAjaxContent($block);
        return $this->getResult();
    }

    //########################################
}