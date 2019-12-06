<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnCheckoutCartSaveAfter
 * @package Convead\Tracker\Observer
 */
class OnCheckoutCartSaveAfter extends AbstractObserver implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($item = $this->dataProvider->getLastAddedItem()) {
            if ($parentItemId = $item->getParentItemId()) {
                foreach ($item->getQuote()->getAllItems() as $item) {
                    if ($parentItemId == $item->getId()) {
                        break;
                    }
                }
            }

            try {
                $this->trackerApi->apiAddToCart($item);
                $this->dataProvider->setLastAddedItem(null);
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage());
            }
        }

        return $this;
    }
}
