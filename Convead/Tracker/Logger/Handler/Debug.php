<?php

namespace Convead\Tracker\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;

class Debug extends Base
{
    /**
     * @var string
     */
    protected $location = '/var/log/tracker_debug/';

    /**
     * @var int
     */
    protected $loggerType = \Monolog\Logger::DEBUG;

    /**
     * Debug constructor.
     * @param DriverInterface $filesystem
     * @throws \Exception
     */
    public function __construct(
        DriverInterface $filesystem
    ) {
        $currentDate = new \DateTime();
        $filename = $currentDate->format('ymd') . '.log';
        $this->fileName = $this->location . $filename;
        parent::__construct($filesystem);
    }
}
