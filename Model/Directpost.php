<?php
/**
 * Human Element Inc.
 *
 * @package HumanElement_AuthorizenetFix
 * @copyright Copyright (c) 2017 Human Element Inc. (https://www.human-element.com)
 */

namespace HumanElement\AuthorizenetFix\Model;

/**
 * Authorize.net DirectPost payment method model.
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Directpost extends \Magento\Authorizenet\Model\Directpost
{
    /**
     * Register order cancellation. Return money to customer if needed.
     *
     * @param \Magento\Sales\Model\Order $order
     * @param string $message
     * @param bool $voidPayment
     * @return void
     */
    protected function declineOrder(\Magento\Sales\Model\Order $order, $message = '', $voidPayment = true)
    {
        try {
            $response = $this->getResponse();
            if (
                $voidPayment && $response->getXTransId() && strtoupper($response->getXType())
                == self::REQUEST_TYPE_AUTH_ONLY
            ) {
                $order->getPayment()->setTransactionId(null)->setParentTransactionId($response->getXTransId())->void();
            }

            // Call cancel instead of registerCancellation
            $order->cancel();

            // Preserve comment being added to order
            if (!empty($message)) {
                $order->addStatusHistoryComment($message, false);
            }

            // Save order
            $order->save();
        } catch (\Exception $e) {
            //quiet decline
            $this->getPsrLogger()->critical($e);
        }
    }
}
