<?php

namespace Smile\Manifestation\ViewModel;

use Magento\Framework\Exception\NoSuchEntityException;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class Manifestation
 */
class Manifestation implements ArgumentInterface
{
    /**
     * Manifestation Repository
     *
     * @var ManifestationRepositoryInterface
     */
    protected $manifestationRepository;

    /**
     * Manifestation Interface
     *
     * @var ManifestationInterface
     */
    protected $manifestation = null;

    /**
     * Request instance
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Manifestation constructor
     *
     * @param ManifestationRepositoryInterface $manifestationRepository
     * @param RequestInterface $request
     */
    public function __construct(
        ManifestationRepositoryInterface $manifestationRepository,
        RequestInterface $request
    ) {
        $this->manifestationRepository = $manifestationRepository;
        $this->request = $request;
    }

    /**
     * Get Manifestation
     *
     * @return ManifestationInterface
     *
     * @throws NoSuchEntityException
     */
    public function getManifestation()
    {
        if (is_null($this->manifestation)) {
            $this->manifestation = $this->manifestationRepository->getById($this->request->getParam(ManifestationInterface::ID));
        }

        return $this->manifestation;
    }
}
