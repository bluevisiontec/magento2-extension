<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Walmart\Template\Description;

use Ess\M2ePro\Controller\Adminhtml\Walmart\Template;

class NewAction extends Template
{
    public function execute()
    {
        $this->_forward('edit');
    }
}