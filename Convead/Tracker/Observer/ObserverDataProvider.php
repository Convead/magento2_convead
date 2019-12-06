<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Magento\Framework\DataObject;

/**
 * Class ObserverDataProvider
 * @package Convead\Tracker\Observer
 */
class ObserverDataProvider extends DataObject
{
    /**
     * @param $quoteItems
     */
    public function setOldQuoteItems($quoteItems)
    {
        $this->setData('old_quote_items', $quoteItems);
    }

    /**
     * @return mixed
     */
    public function getOldQuoteItems()
    {
        return $this->getData('old_quote_items');
    }

    /**
     * @param $quoteItem
     */
    public function setLastAddedItem($quoteItem)
    {
        $this->setData('last_added_item', $quoteItem);
    }

    /**
     * @return mixed
     */
    public function getLastAddedItem()
    {
        return $this->getData('last_added_item');
    }
}
