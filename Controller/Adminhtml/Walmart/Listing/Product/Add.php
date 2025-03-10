<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Walmart\Listing\Product;

use Ess\M2ePro\Controller\Adminhtml\Walmart\Main;

abstract class Add extends Main
{
    protected $sessionKey = 'walmart_listing_product_add';
    protected $listing = NULL;

    //########################################

    protected function setSessionValue($key, $value)
    {
        $sessionData = $this->getSessionValue();
        $sessionData[$key] = $value;

        $this->getHelper('Data\Session')->setValue($this->sessionKey, $sessionData);

        return $this;
    }

    protected function getSessionValue($key = NULL)
    {
        $sessionData = $this->getHelper('Data\Session')->getValue($this->sessionKey);

        if (is_null($sessionData)) {
            $sessionData = array();
        }

        if (is_null($key)) {
            return $sessionData;
        }

        return isset($sessionData[$key]) ? $sessionData[$key] : NULL;
    }

    // ---------------------------------------

    protected function clearSession()
    {
        $this->getHelper('Data\Session')->setValue($this->sessionKey, NULL);
    }

    //########################################

    /**
     * @return \Ess\M2ePro\Model\Listing
     */
    protected function getListing()
    {
        if (is_null($this->listing)) {
            $this->listing = $this->walmartFactory->getObjectLoaded('Listing', $this->getRequest()->getParam('id'));
        }

        return $this->listing;
    }

    //########################################

    protected function setCategoryTemplate($productsIds, $templateId)
    {
        $connWrite = $this->resourceConnection->getConnection();
        $tableWalmartListingProduct = $this->activeRecordFactory->getObject('Walmart\Listing\Product')
            ->getResource()->getMainTable();

        $productsIds = array_chunk($productsIds, 1000);
        foreach ($productsIds as $productsIdsChunk) {
            $connWrite->update($tableWalmartListingProduct, array(
                'template_category_id' => $templateId
            ), '`listing_product_id` IN ('.implode(',', $productsIdsChunk).')'
            );
        }
    }

    //########################################

    protected function deleteListingProducts($ids)
    {
        $ids = array_map('intval', $ids);

        /** @var \Ess\M2ePro\Model\ResourceModel\Listing\Product\Collection $collection */
        $collection = $this->walmartFactory->getObject('Listing\Product')->getCollection()
            ->addFieldToFilter('id', array('in' => $ids));

        foreach ($collection->getItems() as $listingProduct) {
            /**@var \Ess\M2ePro\Model\Listing\Product $listingProduct */
            $listingProduct->delete();
        }

        $listingProductAddIds = $this->getListing()->getSetting('additional_data', 'adding_listing_products_ids');
        if (empty($listingProductAddIds)) {
            return;
        }
        $listingProductAddIds = array_map('intval', $listingProductAddIds);
        $listingProductAddIds = array_diff($listingProductAddIds, $ids);

        $this->getListing()->setSetting('additional_data', 'adding_listing_products_ids', $listingProductAddIds);
        $this->getListing()->save();
    }

    //########################################

    protected function setWizardStep($step)
    {
        $wizardHelper = $this->getHelper('Module\Wizard');

        if (!$wizardHelper->isActive(\Ess\M2ePro\Helper\View\Walmart::WIZARD_INSTALLATION_NICK)) {
            return;
        }

        $wizardHelper->setStep(\Ess\M2ePro\Helper\View\Walmart::WIZARD_INSTALLATION_NICK, $step);
    }

    protected function endWizard()
    {
        /** @var \Ess\M2ePro\Helper\Module\Wizard $wizardHelper */
        $wizardHelper = $this->getHelper('Module\Wizard');

        if (!$wizardHelper->isActive(\Ess\M2ePro\Helper\View\Walmart::WIZARD_INSTALLATION_NICK)) {
            return;
        }

        $wizardHelper->setStatus(
            \Ess\M2ePro\Helper\View\Walmart::WIZARD_INSTALLATION_NICK,
            \Ess\M2ePro\Helper\Module\Wizard::STATUS_COMPLETED
        );

        $this->getHelper('Magento')->clearMenuCache();
    }

    //########################################
}