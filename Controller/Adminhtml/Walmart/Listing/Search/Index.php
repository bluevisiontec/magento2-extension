<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Walmart\Listing\Search;

use Ess\M2ePro\Controller\Adminhtml\Walmart\Main;

class Index extends Main
{
    //########################################

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ess_M2ePro::walmart_listings_search');
    }

    //########################################

    public function execute()
    {
        if ($this->isAjax()) {
            $listingType = $this->getRequest()->getParam('listing_type', false);

            if ($listingType == \Ess\M2ePro\Block\Adminhtml\Listing\Search\TypeSwitcher::LISTING_TYPE_LISTING_OTHER) {
                $gridBlock = 'Walmart\Listing\Search\Other\Grid';
            } else {
                $gridBlock = 'Walmart\Listing\Search\Product\Grid';
            }

            $this->setAjaxContent(
                $this->createBlock($gridBlock)
            );
            return $this->getResult();
        }

        $this->addContent($this->createBlock('Walmart\Listing\Search'));
        $this->getResultPage()->getConfig()->getTitle()->prepend($this->__('Search Products'));

        return $this->getResult();
    }

    //########################################
}