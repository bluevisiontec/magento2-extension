<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Model\ResourceModel\Walmart\Listing\Auto\Category;

class Group extends \Ess\M2ePro\Model\ResourceModel\ActiveRecord\Component\Child\AbstractModel
{
    //########################################

    public function _construct()
    {
        $this->_init('m2epro_walmart_listing_auto_category_group', 'listing_auto_category_group_id');
        $this->_isPkAutoIncrement = false;
    }

    //########################################
}