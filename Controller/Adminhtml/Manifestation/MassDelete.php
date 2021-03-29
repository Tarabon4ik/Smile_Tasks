<?php

declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Manifestation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Ui\Component\MassAction\Filter;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;
use Smile\Manifestation\Model\ResourceModel\Manifestation\CollectionFactory as ManifestationCollectionFactory;

/**
 * MassDelete Controller
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation';

    /**
     * @var ManifestationRepositoryInterface
     */
    private $manifestationRepository;

    /**
     * @var ManifestationCollectionFactory
     */
    protected $manifestationCollectionFactory;

    /**
     * @var Filter
     */
    private $massActionFilter;

    /**
     * @param Context $context
     * @param ManifestationRepositoryInterface $manifestationRepository
     * @param ManifestationCollectionFactory $manifestationCollectionFactory
     * @param Filter $massActionFilter
     */
    public function __construct(
        Context $context,
        ManifestationRepositoryInterface $manifestationRepository,
        ManifestationCollectionFactory $manifestationCollectionFactory,
        Filter $massActionFilter
    ) {
        parent::__construct($context);
        $this->manifestationRepository = $manifestationRepository;
        $this->manifestationCollectionFactory = $manifestationCollectionFactory;
        $this->massActionFilter = $massActionFilter;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        if ($this->getRequest()->isPost() !== true) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));

            return $this->resultRedirectFactory->create()->setPath('*/*');
        }

        $collection = $this->massActionFilter->getCollection($this->manifestationCollectionFactory->create());
        $deletedItemsCount = 0;

        /** @var \Smile\Manifestation\Model\Manifestation $item */
        foreach ($collection as $item) {
            try {
                $id = (int)$item->getId();
                $this->manifestationRepository->deleteById($id);
                $deletedItemsCount++;
            } catch (CouldNotDeleteException $e) {
                $errorMessage = __('[ID: %1] ', $id) . $e->getMessage();
                $this->messageManager->addErrorMessage($errorMessage);
            }
        }
        $this->messageManager->addSuccessMessage(__('You deleted %1 Manifestation(s).', $deletedItemsCount));

        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}
