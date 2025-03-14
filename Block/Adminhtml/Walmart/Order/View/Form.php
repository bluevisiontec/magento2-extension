<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Block\Adminhtml\Walmart\Order\View;

use Ess\M2ePro\Block\Adminhtml\Magento\AbstractContainer;

class Form extends AbstractContainer
{
    protected $_template = 'walmart/order.phtml';

    protected $storeManager;

    public $shippingAddress = array();

    public $realMagentoOrderId = NULL;

    /** @var \Ess\M2ePro\Model\Order */
    public $order;

    //########################################

    public function __construct(
        \Magento\Store\Model\StoreManager $storeManager,
        \Ess\M2ePro\Block\Adminhtml\Magento\Context\Widget $context,
        array $data = []
    )
    {
        $this->storeManager = $storeManager;

        parent::__construct($context, $data);
    }

    public function _construct()
    {
        parent::_construct();

        // Initialization block
        // ---------------------------------------
        $this->setId('walmartOrderViewForm');
        // ---------------------------------------

        $this->order = $this->getHelper('Data\GlobalData')->getValue('order');
    }

    protected function _beforeToHtml()
    {
        // Magento order data
        // ---------------------------------------
        $this->realMagentoOrderId = NULL;

        $magentoOrder = $this->order->getMagentoOrder();
        if (!is_null($magentoOrder)) {
            $this->realMagentoOrderId = $magentoOrder->getRealOrderId();
        }
        // ---------------------------------------

        $data = array(
            'class' => 'primary',
            'label'   => $this->__('Edit'),
            'onclick' => "OrderEditItemObj.openEditShippingAddressPopup({$this->order->getId()});",
        );
        $buttonBlock = $this->createBlock('Magento\Button')->setData($data);
        $this->setChild('edit_shipping_info', $buttonBlock);

        // ---------------------------------------
        if (!is_null($magentoOrder) && $magentoOrder->hasShipments()) {
            $url = $this->getUrl('*/order/resubmitShippingInfo', array('id' => $this->order->getId()));
            $data = array(
                'class'   => 'primary',
                'label'   => $this->__('Resend Shipping Information'),
                'onclick' => 'setLocation(\''.$url.'\');',
            );
            $buttonBlock = $this->createBlock('Magento\Button')->setData($data);
            $this->setChild('resubmit_shipping_info', $buttonBlock);
        }
        // ---------------------------------------

        // Shipping data
        // ---------------------------------------
        /** @var $shippingAddress \Ess\M2ePro\Model\Walmart\Order\ShippingAddress */
        $shippingAddress = $this->order->getShippingAddress();

        $this->shippingAddress = $shippingAddress->getData();
        $this->shippingAddress['country_name'] = $shippingAddress->getCountryName();
        // ---------------------------------------

        $this->jsUrl->addUrls([
            'order/getDebugInformation' => $this->getUrl(
                '*/order/getDebugInformation/', array('id' => $this->getRequest()->getParam('id'))
            ),
            'getEditShippingAddressForm' => $this->getUrl(
                '*/walmart_order_shippingAddress/edit/', array('id' => $this->getRequest()->getParam('id'))
            ),
            'saveShippingAddress' => $this->getUrl(
                '*/walmart_order_shippingAddress/save', array('id' => $this->getRequest()->getParam('id'))
            ),
        ]);

        $this->jsPhp->addConstants(
            $this->getHelper('Data')->getClassConstants('\Ess\M2ePro\Controller\Adminhtml\Order\EditItem')
        );

        $this->setChild('shipping_address', $this->createBlock('Walmart\Order\Edit\ShippingAddress'));
        $this->setChild('item', $this->createBlock('Walmart\Order\View\Item'));
        $this->setChild('item_edit', $this->createBlock('Order\Item\Edit'));
        $this->setChild('log', $this->createBlock('Order\View\Log\Grid'));

        return parent::_beforeToHtml();
    }

    private function getStore()
    {
        if (is_null($this->order->getData('store_id'))) {
            return null;
        }

        try {
            $store = $this->storeManager->getStore($this->order->getData('store_id'));
        } catch (\Exception $e) {
            return null;
        }

        return $store;
    }

    public function isCurrencyAllowed()
    {
        $store = $this->getStore();

        if (is_null($store)) {
            return true;
        }

        return $this->modelFactory->getObject('Currency')->isAllowed(
            $this->order->getChildObject()->getCurrency(), $store
        );
    }

    public function hasCurrencyConversionRate()
    {
        $store = $this->getStore();

        if (is_null($store)) {
            return true;
        }

        return $this->modelFactory->getObject('Currency')->getConvertRateFromBase(
            $this->order->getChildObject()->getCurrency(), $store
        ) != 0;
    }

    public function formatPrice($currencyName, $priceValue)
    {
        return $this->modelFactory->getObject('Currency')->formatPrice($currencyName, $priceValue);
    }
}