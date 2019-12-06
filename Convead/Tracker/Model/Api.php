<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Model;

use Convead\Tracker\Helper\Data as TrackerHelper;
use Convead\Tracker\Lib\ConveadTrackerFactory;
use Convead\Tracker\Lib\ConveadTracker;
use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class Api
 * @package Convead\Tracker\Model
 */
class Api
{
    /**
     * @var ConveadTracker
     */
    protected $tracker;

    /**
     * @var TrackerHelper
     */
    protected $trackerHelper;

    /**
     * @var ConveadTrackerFactory
     */
    protected $trackerFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * Api constructor.
     * @param TrackerHelper $trackerHelper
     * @param ConveadTrackerFactory $trackerFactory
     * @param Session $customerSession
     * @param CookieManagerInterface $cookieManager
     */
    public function __construct(
        TrackerHelper $trackerHelper,
        ConveadTrackerFactory $trackerFactory,
        Session $customerSession,
        CookieManagerInterface $cookieManager
    ) {
        $this->trackerHelper = $trackerHelper;
        $this->trackerFactory = $trackerFactory;
        $this->customerSession = $customerSession;
        $this->cookieManager = $cookieManager;
        $this->_initConvead();
    }

    /**
     * Init Anonymous Tracker
     */
    protected function _initConveadAnonym()
    {
        $key = $this->trackerHelper->getConveadApiKey();
        $this->tracker = $this->trackerFactory->create(['api_key' => $key]);
    }

    /**
     * Init Tracker
     *
     * @param bool $order
     */
    protected function _initConvead($order = false)
    {
        $key = $this->trackerHelper->getConveadApiKey();
        $guest_uid = $this->cookieManager->getCookie('convead_guest_uid', false);
        $customerId = $this->customerSession->getCustomerId() ?? false;

        if ($order) {
            $visitorInfo = [];
            if ($address = $order->getBillingAddress()) {
                if ($firstName = $address->getFirstname()) {
                    $visitorInfo['first_name'] = $firstName;
                }
                if ($lastName = $address->getLastname()) {
                    $visitorInfo['last_name'] = $lastName;
                }
                if ($phone = $address->getTelephone()) {
                    $visitorInfo['phone'] = $phone;
                }
                if ($email = $address->getEmail()) {
                    $visitorInfo['email'] = $email;
                }
            }

            $this->tracker = $this->trackerFactory->create([
                'api_key'       => $key,
                'domain'        => $_SERVER['HTTP_HOST'],
                'guest_uid'     => $guest_uid,
                'visitor_uid'   => $customerId,
                'visitor_info'  => $visitorInfo
            ]);
        } else {
            $this->tracker = $this->trackerFactory->create([
                'api_key'       => $key,
                'domain'        => $_SERVER['HTTP_HOST'],
                'guest_uid'     => $guest_uid,
                'visitor_uid'   => $customerId
            ]);
        }
    }

    /**
     * @param $item
     * @return $this
     */
    public function apiAddToCart($item)
    {
        $product_id = $item->getProductId();
        $qnt = $item->getQtyToAdd();
        $price = $item->getPrice();
        $product_name = $item->getName();
        $product_url = $item->getProduct()->getProductUrl();

        $this->tracker->eventAddToCart($product_id, $qnt, $price, $product_name, $product_url);

        return $this;
    }

    /**
     * @param $item
     * @return $this
     */
    public function apiRemoveFromCart($item)
    {
        $product_id = $item->getProductId();
        $qnt = $item->getQty();
        $product_name = $item->getName();
        $product_url = $item->getProduct()->getProductUrl();
        $this->tracker->eventRemoveFromCart($product_id, $qnt, $product_name, $product_url);

        return $this;
    }

    /**
     * @param $items
     * @return $this
     */
    public function apiUpdateCart($items)
    {
        $order_array = [];
        foreach ($items as $item) {
            $order_array[] = [
                'product_id'    => $item->getProductId(),
                'qnt'           => $item->getQty(),
                'price'         => $item->getPrice()
            ];
        }

        $this->tracker->eventUpdateCart($order_array);

        return $this;
    }

    /**
     * @param $order
     * @return $this
     */
    public function apiPurchase($order)
    {
        $this->_initConvead($order);
        $orderData = $this->getOrderData($order);
        $this->tracker->eventOrder(
            $orderData->getData('order_id'),
            $orderData->getData('revenue'),
            $orderData->getData('items'),
            $orderData->getData('state')
        );

        return $this;
    }

    /**
     * @param $order
     * @return $this|bool
     */
    public function apiOrderSetState($order)
    {
        $this->_initConveadAnonym();
        $orderData = $this->getOrderData($order);
        if (!$orderData->getData('state')) {
            return false;
        }

        $this->tracker->webHookOrderUpdate(
            $orderData->getData('order_id'),
            $orderData->getData('state'),
            $orderData->getData('revenue'),
            $orderData->getData('items')
        );

        return $this;
    }

    /**
     * @param $order
     * @return $this
     */
    public function apiOrderDelete($order)
    {
        $this->_initConveadAnonym();
        $order_id = $order->getIncrementId();
        $this->tracker->webHookOrderUpdate($order_id, 'cancelled');

        return $this;
    }

    /**
     * @param $order
     * @return \Magento\Framework\DataObject
     */
    private function getOrderData($order)
    {
        $orderData = new \Magento\Framework\DataObject();

        $items = $order->getAllVisibleItems();
        $order_array = [];
        foreach ($items as $item) {
            $order_array[] = [
                'product_id'    => $item->getProductId(),
                'qnt'           => $item->getQtyOrdered(),
                'price'         => $item->getPrice()
            ];
        }

        $orderData->setData('order_id', $order->getIncrementId());
        $orderData->setData('revenue', $order->getGrandTotal());
        $orderData->setData('items', $order_array);
        $orderData->setData('state', $this->switchState($order->getState()));

        return $orderData;
    }

    /**
     * @param $state
     * @return string
     */
    private function switchState($state) {
        switch ($state) {
          case 'processing':
            $state = 'new';
            break;
          case 'payment_review':
            $state = 'paid';
            break;
          case 'complete':
            $state = 'shipped';
            break;
          case 'cancelled':
          case 'closed':
            $state = 'cancelled';
            break;
        }

        return $state;
    }
}
