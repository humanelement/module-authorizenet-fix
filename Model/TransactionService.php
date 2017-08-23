<?php
/**
 * Human Element Inc.
 *
 * @package HumanElement_AuthorizenetFix
 * @copyright Copyright (c) 2017 Human Element Inc. (https://www.human-element.com)
 */

namespace HumanElement\AuthorizenetFix\Model;

use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Xml\Security;
use Magento\Payment\Model\Method\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class TransactionService
 * @package Magento\Authorizenet\Model
 */
class TransactionService extends \Magento\Authorizenet\Model\TransactionService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param Security $xmlSecurityHelper
     * @param Logger $logger
     * @param ZendClientFactory $httpClientFactory
     * @param LoggerInterface $psrLogger
     */
    public function __construct(
        Security $xmlSecurityHelper,
        Logger $logger,
        ZendClientFactory $httpClientFactory,
        LoggerInterface $psrLogger
    ) {
        parent::__construct($xmlSecurityHelper, $logger, $httpClientFactory);
        // Redefine logger to be the psr logger
        $this->logger = $psrLogger;
    }
}
