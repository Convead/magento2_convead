<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Convead\Tracker\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Customer section
 */
class VisitorInfo implements SectionSourceInterface
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * VisitorInfo constructor.
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CustomerSession $customerSession
    ) {
        $this->customerSession = $customerSession;
    }

    /**
     * @param $customer
     * @return bool
     */
    public function getCustomerPhone($customer)
    {
        if ($billingAddress = $customer->getDefaultBillingAddress()) {
            return $billingAddress->getTelephone();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        $settings = [];
        if ($this->customerSession->isLoggedIn()) {
            $customer = $this->customerSession->getCustomer();
            if ($customer->getId()) {
                $settings['visitor_uid'] = $customer->getId();
            }

            $settings['visitor_info'] = [];
            if ($customer->getFirstname()) {
                $settings['visitor_info']['first_name'] = $customer->getFirstname();
            }
            if ($customer->getLastname()) {
                $settings['visitor_info']['last_name'] = $customer->getLastname();
            }
            if ($customer->getEmail()) {
                $settings['visitor_info']['email'] = $customer->getEmail();
            }
            if ($customerPhone = $this->getCustomerPhone($customer)) {
                $settings['visitor_info']['phone'] = $customerPhone;
            }
            if ($customer->getDob()) {
                $settings['visitor_info']['date_of_birth'] = date('Y-m-d', strtotime($customer->getDob()));
            }
            if ($customer->getGender()) {
                $settings['visitor_info']['gender'] = $customer->getGender() == 1 ? 'male' : 'female';
            }
        }

        return ['visitor' => $settings];
    }
}
