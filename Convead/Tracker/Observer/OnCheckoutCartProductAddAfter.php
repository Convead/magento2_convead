<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnCheckoutCartProductAddAfter
 * @package Convead\Tracker\Observer
 */
class OnCheckoutCartProductAddAfter extends AbstractObserver implements ObserverInterface
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

        $this->dataProvider->setLastAddedItem($observer->getEvent()->getQuoteItem());

        return $this;
    }
}
