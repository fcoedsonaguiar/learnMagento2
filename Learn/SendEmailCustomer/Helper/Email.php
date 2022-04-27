<?php

namespace Learn\SendEmailCustomer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Email extends AbstractHelper
{

// Email type for to send to customer
    const XML_PATH_NAME_RECIPIENT_SUPPORT = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_SUPPORT = 'trans_email/ident_support/email';

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var ScopeConfigInterface;
     */
    protected $scopeConfigInterface;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfigInterface
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfigInterface = $scopeConfigInterface;
        $this->logger = $context->getLogger();
    }
    public function sendEmail($observer)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $event = $observer->getEvent();
        $customer = $event->getCustomer();

    // Array customer data for template vars
        $CustomerData = [
            'firstname' => $customer->getFirstname(),
            'lastname' => $customer->getLastname(),
            'email' => $customer->getEmail()
        ];

        try {
            $sender = [
                'name' => $this->escaper->escapeHtml(
                    $this->scopeConfig->getValue(self::XML_PATH_NAME_RECIPIENT_SUPPORT, $storeScope)
                ),
                'email' => $this->escaper->escapeHtml(
                    $this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT_SUPPORT, $storeScope)
                ),
            ];
            $this->inlineTranslation->suspend();

        //  Set id email template configured into admin painel
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('3')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($CustomerData)
                ->setFromByScope($sender)
                ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT_SUPPORT, $storeScope))
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();

        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
