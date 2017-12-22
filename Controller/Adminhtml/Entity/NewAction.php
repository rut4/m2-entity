<?php

namespace Oggetto\Entities\Controller\Adminhtml\Entity;

use Magento\Backend\App\Action;

class NewAction extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Oggetto_Entities::entities';

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    private $resultForwardFactory;

    public function __construct(
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        Action\Context $context
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute(): \Magento\Backend\Model\View\Result\Forward
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
