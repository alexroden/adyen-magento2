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
 * Adyen Payment module (https://www.adyen.com/)
 *
 * Copyright (c) 2015 Adyen BV (https://www.adyen.com/)
 * See LICENSE.txt for license details.
 *
 * Author: Adyen <magento@adyen.com>
 */

namespace Adyen\Payment\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class AddressDataBuilder implements BuilderInterface
{
    /**
     * @var \Adyen\Payment\Helper\Requests
     */
    private $adyenRequestsHelper;

    /**
     * AddressDataBuilder constructor.
     *
     * @param \Adyen\Payment\Helper\Requests $adyenRequestsHelper
     */
    public function __construct(
        \Adyen\Payment\Helper\Requests $adyenRequestsHelper
    ) {
        $this->adyenRequestsHelper = $adyenRequestsHelper;
    }

    /**
     * Add delivery\billing details into request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        /** @var \Magento\Payment\Gateway\Data\PaymentDataObject $paymentDataObject */
        $paymentDataObject = \Magento\Payment\Gateway\Helper\SubjectReader::readPayment($buildSubject);
        $order = $paymentDataObject->getOrder();
        $payment = $paymentDataObject->getPayment();
        $billingAddress = $order->getBillingAddress();
        $channel = $payment->getAdditionalInformation('channel') ?: '';
        $shippingAddress = null;
        if ($channel === '') {
            $shippingAddress = $order->getShippingAddress();
        }

        $request['body'] = $this->adyenRequestsHelper->buildAddressData(
            $billingAddress,
            $shippingAddress,
            $order->getStoreId(),
            []
        );

        return $request;
    }
}
