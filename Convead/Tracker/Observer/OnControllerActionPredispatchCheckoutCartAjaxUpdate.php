<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnControllerActionPredispatchCheckoutCartAjaxUpdate
 * @package Convead\Tracker\Observer
 */
class OnControllerActionPredispatchCheckoutCartAjaxUpdate extends AbstractObserver implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->trackerHelper->isEnabledConveadTracker() || !$this->trackerHelper->getConveadApiKey()) {
            return $this;
        }

        $items = $cart = $this->checkoutSession->getQuote()->getAllVisibleItems();

        $oldItems = [];
        foreach ($items as $item) {
            $oldItems[$item->getId()] = $item->getQty();
        }
        $this->dataProvider->setOldQuoteItems($oldItems);

        return $this;
    }
}
