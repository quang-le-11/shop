<?php

namespace ViMagento\Shop\Block\Adminhtml;

class ViewProductButton extends \Magento\Backend\Block\Widget\Container
{
    protected $_product;
    protected $_coreRegistry = null;
    protected $_emulation;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product $product,
        \Magento\Store\Model\App\Emulation $emulation,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_product = $product;
        $this->_request = $context->getRequest();
        $this->_emulation = $emulation;
        parent::__construct($context, $data);
    }

    public function getButtonData()
    {
        return [
            'label' => __('View'),
            'on_click' => sprintf("window.open('%s')", $this->_getProductUrl()),
            'class' => 'view disable',
            'sort_order' => 20
        ];
    }
    /**
     * Return product frontend url depends on active store
     *
     * @return mixed
     */
    protected function _getProductUrl()
    {
        $store = $this->_request->getParam('store');
        if (!$store) {
            $this->_emulation->startEnvironmentEmulation(null, \Magento\Framework\App\Area::AREA_FRONTEND, true);
            $productUrl = $this->_product->loadByAttribute('entity_id', $this->_coreRegistry->registry('product')->getId())->getProductUrl();
            $this->_emulation->stopEnvironmentEmulation();
            return $productUrl;
        } else {
            return $this->_product
                ->loadByAttribute('entity_id', $this->_coreRegistry->registry('product')->getId()
                )->setStoreId($store)->getUrlInStore();
        }
    }
}
