<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Walmart\Synchronization\Log;

class Index extends \Ess\M2ePro\Controller\Adminhtml\Walmart\Settings
{
    //########################################

    public function execute()
    {
        return $this->_redirect('*/developers/index',
            ['active_tab' => \Ess\M2ePro\Block\Adminhtml\Developers\Tabs::TAB_ID_SYNCHRONIZATION_LOG]
        );
    }

    //########################################
}