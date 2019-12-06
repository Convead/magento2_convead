<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnSalesOrderSaveAfter
 * @package Convead\Tracker\Observer
 */
class OnSalesOrderSaveAfter extends AbstractObserver implements ObserverInterface
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
            $this->trackerApi->apiOrderSetState($order);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }
}
