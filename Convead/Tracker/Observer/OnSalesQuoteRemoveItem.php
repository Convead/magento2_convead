<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnSalesQuoteRemoveItem
 * @package Convead\Tracker\Observer
 */
class OnSalesQuoteRemoveItem extends AbstractObserver implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->trackerHelper->isEnabledConveadTracker() || !$this->trackerHelper->getConveadApiKey()) {
            return $this;
        }

        $item = $observer->getEvent()->getQuoteItem();

        try {
            $this->trackerApi->apiRemoveFromCart($item);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }
}
