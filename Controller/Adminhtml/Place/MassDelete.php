<?php
/**
 * Admin Controller MassDelete
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Place;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Ui\Component\MassAction\Filter;
use Smile\Manifestation\Api\PlaceRepositoryInterface;
use Smile\Manifestation\Model\ResourceModel\Place\CollectionFactory as PlaceCollectionFactory;
use Smile\Manifestation\Model\Place;

/**
 * MassDelete Controller
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation_place';

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @var PlaceCollectionFactory
     */
    protected $placeCollectionFactory;

    /**
     * @var Filter
     */
    private $massActionFilter;

    /**
     * @param Context $context
     * @param PlaceRepositoryInterface $placeRepository
     * @param PlaceCollectionFactory $placeCollectionFactory
     * @param Filter $massActionFilter
     */
    public function __construct(
        Context $context,
        PlaceRepositoryInterface $placeRepository,
        PlaceCollectionFactory $placeCollectionFactory,
        Filter $massActionFilter
    ) {
        parent::__construct($context);
        $this->placeRepository = $placeRepository;
        $this->placeCollectionFactory = $placeCollectionFactory;
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

        $collection = $this->massActionFilter->getCollection($this->placeCollectionFactory->create());
        $deletedItemsCount = 0;

        /** @var Place $item */
        foreach ($collection as $item) {
            try {
                $id = (int)$item->getId();
                $this->placeRepository->deleteById($id);
                $deletedItemsCount++;
            } catch (CouldNotDeleteException $e) {
                $errorMessage = __('[ID: %1] ', $id) . $e->getMessage();
                $this->messageManager->addErrorMessage($errorMessage);
            }
        }
        $this->messageManager->addSuccessMessage(__('You deleted %1 Place(s).', $deletedItemsCount));

        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}
