<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Model\Walmart\Connector\Inventory\Get;

abstract class ItemsRequester extends \Ess\M2ePro\Model\Walmart\Connector\Command\Pending\Requester
{
    // ########################################

    public function getRequestData()
    {
        return array();
    }

    public function getCommand()
    {
        return array('inventory', 'get', 'items');
    }

    // ########################################
}