<?php

namespace Learn\NewPage\Block;

use Learn\NewPage\Helper\Helper;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Block extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Constructor
     * @param \Learn\NewPage\Helper\Helper $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(
        Helper $helper,
        Context $context,
        CollectionFactory $productCollectionFactory,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getValueInputAdmin()
    {
        $sendMsgVia_Admin = $this->helper->getConfig('section_id/group_id/text');
        if($this->helper->getSelectValue()) {
            echo $sendMsgVia_Admin;
        } else {
            echo 'treat condition';
        }
    }
}
