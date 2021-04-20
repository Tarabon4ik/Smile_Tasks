<?php

declare(strict_types=1);

namespace Smile\Onepage\Model\Layout;

use Magento\Framework\View\LayoutInterface;
use Magento\PageCache\Model\DepersonalizeChecker;
use Smile\Onepage\Model\Session as ManifestationOnepageSession;

/**
 * Depersonalize customer data
 */
class DepersonalizePlugin
{
    /**
     * @var DepersonalizeChecker
     */
    private $depersonalizeChecker;

    /**
     * @var ManifestationOnepageSession
     */
    private $manifestationSession;

    /**
     * @param DepersonalizeChecker $depersonalizeChecker
     * @param ManifestationOnepageSession $manifestationSession
     * @codeCoverageIgnore
     */
    public function __construct(
        DepersonalizeChecker $depersonalizeChecker,
        ManifestationOnepageSession $manifestationSession
    ) {
        $this->depersonalizeChecker = $depersonalizeChecker;
        $this->manifestationSession = $manifestationSession;
    }

    /**
     * Change sensitive customer data if the depersonalization is needed.
     *
     * @param LayoutInterface $subject
     * @return void
     */
    public function afterGenerateElements(LayoutInterface $subject)
    {
        if ($this->depersonalizeChecker->checkIfDepersonalize($subject)) {
            $this->manifestationSession->clearStorage();
        }
    }
}
