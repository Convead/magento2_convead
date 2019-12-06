<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnAdminOrderCancelAfter
 * @package Convead\Tracker\Observer
 */
class OnAdminOrderCancelAfter extends AbstractObserver implements ObserverInterface
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

        $order = $observer->getEvent()->getOrder();

        try {
            $this->trackerApi->apiOrderDelete($order);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }
}
