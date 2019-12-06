<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Observer;

use Convead\Tracker\Helper\Data as TrackerHelper;
use Convead\Tracker\Model\ConveadTracker;
use Convead\Tracker\Model\Api as TrackerApi;
use Magento\Checkout\Model\Session as CheckoutSession;
use Convead\Tracker\Logger\Logger;
use Convead\Tracker\Observer\ObserverDataProvider as DataProvider;

class AbstractObserver
{
    /**
     * @var TrackerHelper
     */
    protected $trackerHelper;

    /**
     * @var ConveadTracker
     */
    protected $tracker;

    /**
     * @var TrackerApi
     */
    protected $trackerApi;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var ObserverDataProvider
     */
    protected $dataProvider;

    /**
     * AbstractObserver constructor.
     * @param TrackerApi $trackerApi
     * @param TrackerHelper $trackerHelper
     * @param Logger $logger
     * @param CheckoutSession $checkoutSession
     * @param ObserverDataProvider $dataProvider
     */
    public function __construct(
        TrackerApi $trackerApi,
        TrackerHelper $trackerHelper,
        Logger $logger,
        CheckoutSession $checkoutSession,
        DataProvider $dataProvider
    ) {
        $this->trackerApi = $trackerApi;
        $this->trackerHelper = $trackerHelper;
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
        $this->dataProvider = $dataProvider;
    }
}
