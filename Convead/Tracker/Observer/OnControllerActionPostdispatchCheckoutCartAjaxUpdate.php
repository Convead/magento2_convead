<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OnControllerActionPostdispatchCheckoutCartAjaxUpdate
 * @package Convead\Tracker\Observer
 */
class OnControllerActionPostdispatchCheckoutCartAjaxUpdate extends AbstractObserver implements ObserverInterface
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

        $oldItems = $this->dataProvider->getOldQuoteItems();
        $items = $this->checkoutSession->getQuote()->getAllVisibleItems();

        foreach ($items as $item) {
            if ($oldItems[$item->getId()] != $item->getQty()) {
                try {
                    $this->trackerApi->apiUpdateCart($items);
                    break;
                } catch (\Exception $e) {
                    $this->logger->debug($e->getMessage());
                }
            }
        }

        return $this;
    }
}
