<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnCheckoutCartUpdateItemsAfter
 * @package Convead\Tracker\Observer
 */
class OnCheckoutCartUpdateItemsAfter extends AbstractObserver implements ObserverInterface
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

        $items = $observer->getEvent()->getCart()->getQuote()->getAllVisibleItems();

        try {
            $this->trackerApi->apiUpdateCart($items);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }
}
