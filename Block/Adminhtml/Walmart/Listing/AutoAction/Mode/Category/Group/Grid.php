<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Block\Adminhtml\Walmart\Listing\AutoAction\Mode\Category\Group;

class Grid extends \Ess\M2ePro\Block\Adminhtml\Listing\AutoAction\Mode\Category\Group\Grid
{
    //########################################

    public function getGridUrl()
    {
        return $this->getUrl('*/walmart_listing_autoAction/getCategoryGroupGrid', array('_current' => true));
    }

    //########################################
}