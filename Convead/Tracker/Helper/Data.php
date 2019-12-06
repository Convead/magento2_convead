<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 * @package Convead\Tracker\Helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_CONVEAD_ENABLED = 'sales/convead_tracker/enabled';

    const XML_PATH_CONVEAD_API_KEY = 'sales/convead_tracker/api_key';

    /**
     * @return mixed
     */
    public function getConveadApiKey()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONVEAD_API_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isEnabledConveadTracker()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CONVEAD_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
