<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json as Serializer;
use Magento\Framework\Registry;
use Convead\Tracker\Helper\Data as ConveadHelper;

/**
 * Main block for module
 *
 * @package Convead\Tracker\Block
 */
class Tracking extends Template
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ConveadHelper
     */
    private $conveadHelper;

    /**
     * Tracking constructor.
     * @param Template\Context $context
     * @param Serializer $serializer
     * @param Registry $registry
     * @param ConveadHelper $conveadHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Serializer $serializer,
        Registry $registry,
        ConveadHelper $conveadHelper,
        array $data = []
    ) {
        $this->serializer = $serializer;
        $this->registry = $registry;
        $this->conveadHelper = $conveadHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) ($this->conveadHelper->isEnabledConveadTracker() && $this->conveadHelper->getConveadApiKey());
    }

    /**
     * @return mixed
     */
    public function getConveadApiKey()
    {
        return $this->conveadHelper->getConveadApiKey();
    }

    /**
     * @return string
     */
    public function getProductConfig()
    {
        $config = [];
        if ($product = $this->getProduct()) {
            $reg = '/(.*?)(.html).*/';
            $productUrl = preg_replace($reg, '$1$2', $product->getProductUrl());
            $config = [
                'product_id'    => $product->getId(),
                'product_name'  => htmlspecialchars($product->getName()),
                'product_url'   => $productUrl
            ];

            if ($category = $product->getCategory()) {
                $config['category_id'] = $category->getId();
            }
        }

        return $this->serializer->serialize($config);
    }

    /**
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        return $this->registry->registry('product');
    }
}
