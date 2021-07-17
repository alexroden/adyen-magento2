<?php
/**
 *                       ######
 *                       ######
 * ############    ####( ######  #####. ######  ############   ############
 * #############  #####( ######  #####. ######  #############  #############
 *        ######  #####( ######  #####. ######  #####  ######  #####  ######
 * ###### ######  #####( ######  #####. ######  #####  #####   #####  ######
 * ###### ######  #####( ######  #####. ######  #####          #####  ######
 * #############  #############  #############  #############  #####  ######
 *  ############   ############  #############   ############  #####  ######
 *                                      ######
 *                               #############
 *                               ############
 *
 * Adyen Payment Module
 *
 * Copyright (c) 2018 Adyen B.V.
 * This file is open source and available under the MIT license.
 * See the LICENSE file for more info.
 *
 * Author: Adyen <magento@adyen.com>
 */

namespace Adyen\Payment\Model;

use Adyen\Payment\Api\OrderCheckoutInitiateInterface;

class OrderCheckoutInitiate implements OrderCheckoutInitiateInterface
{
    /**
     * @var \Magento\Quote\Model\QuoteIdMaskFactory
     */
    protected $_quoteIdMaskFactory;

    /**
     * @var \Adyen\Payment\Logger\AdyenLogger
     */
    private $adyenLogger;

    /**
     * @var \Adyen\Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * OrderCheckoutInitiate constructor.
     *
     * @param \Magento\Quote\Model\QuoteIdMaskFactory    $quoteIdMaskFactory
     * @param \Magento\Checkout\Model\Session            $checkoutSession
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_quoteIdMaskFactory = $quoteIdMaskFactory;

        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param string $cartId
     *
     * @return bool
     */
    public function initiateCheckout($cartId)
    {
        $quoteIdMask = $this->_quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        $quoteId = $quoteIdMask->getQuoteId();

        $this->checkoutSession->setQuoteId($quoteId);

        return $this->checkoutSession->hasQuote();
    }
}
