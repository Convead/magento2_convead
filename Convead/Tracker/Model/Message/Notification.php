<?php
/**
 * Copyright © Convead. All rights reserved.
 */
namespace Convead\Tracker\Model\Message;

use Magento\Framework\Notification\MessageInterface;
use Convead\Tracker\Helper\Data as ConveadHelper;
use Magento\Backend\Model\UrlInterface;

/**
 * Main block for module
 *
 * @package Convead\Tracker\Message
 */
class Notification implements MessageInterface
{
    /**
     * @var ConveadHelper
     */
    private $helper;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Notification constructor.
     * @param ConveadHelper $helper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConveadHelper $helper,
        UrlInterface $urlBuilder
    ) {
        $this->helper = $helper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritDoc
     */
    public function getIdentity()
    {
        return md5('CONVEAD_TRACKER');
    }

    /**
     * @inheritDoc
     */
    public function isDisplayed()
    {
        if (!$this->helper->getConveadApiKey()) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getText()
    {
        $settingsUrl = $this->urlBuilder->getUrl('adminhtml/system_config/edit', ['section' => 'sales']);

        return '<strong>Convead:</strong> ' . __('The module Convead Tracker is successfully installed! Go to the module %1, '
            . 'and enter the API key from your account Convead. The key can be obtained on the Settings page '
            . 'of your Convead account, when choosing Magento as your shop’s CMS.',
            '<a href="' . $settingsUrl . '">' . __('settings') . '</a>'
        );
    }

    /**
     * @inheritDoc
     */
    public function getSeverity()
    {
        return self::SEVERITY_NOTICE;
    }
}
