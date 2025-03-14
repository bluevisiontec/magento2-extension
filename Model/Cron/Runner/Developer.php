<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Model\Cron\Runner;

class Developer extends AbstractModel
{
    private $allowedTasks = NULL;

    //########################################

    public function getNick()
    {
        return NULL;
    }

    public function getInitiator()
    {
        return \Ess\M2ePro\Helper\Data::INITIATOR_DEVELOPER;
    }

    //########################################

    /**
     * @return \Ess\M2ePro\Model\Cron\Strategy\AbstractModel
     */
    protected function getStrategyObject()
    {
        /** @var \Ess\M2ePro\Model\Cron\Strategy\AbstractModel $strategyObject */
        $strategyObject = $this->modelFactory->getObject('Cron\Strategy\Serial');

        if (!empty($this->allowedTasks)) {
            $strategyObject->setAllowedTasks($this->allowedTasks);
        }

        return $strategyObject;
    }

    //########################################

    /**
     * @param array $tasks
     * @return $this
     */
    public function setAllowedTasks(array $tasks)
    {
        $this->allowedTasks = $tasks;
        return $this;
    }

    protected function isPossibleToRun()
    {
        return true;
    }

    //########################################
}