<?php

/*
 *  VERIFY WHEN CUSTOMER REGISTER INTO STORE
 */

namespace Learn\SendEmailCustomer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Learn\SendEmailCustomer\Helper\Email;
use Magento\Framework\Event\Observer;


class CustomerRegisterObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Event\ObserverInterface
     */
    private $helperEmail;

    public function __construct(
        Email $helperEmail
    )
    {
        $this->helperEmail = $helperEmail;
    }

    public function execute(Observer $observer)
    {
        return $this->helperEmail->sendEmail($observer);
    }
}
