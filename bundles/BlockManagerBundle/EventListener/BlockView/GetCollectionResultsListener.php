<?php

namespace Netgen\Bundle\BlockManagerBundle\EventListener\BlockView;

use Netgen\BlockManager\API\Service\BlockService;
use Netgen\BlockManager\Collection\Result\ResultLoaderInterface;
use Netgen\BlockManager\Collection\Result\ResultSet;
use Netgen\BlockManager\Event\BlockManagerEvents;
use Netgen\BlockManager\Event\CollectViewParametersEvent;
use Netgen\BlockManager\View\View\BlockViewInterface;
use Netgen\BlockManager\View\ViewInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GetCollectionResultsListener implements EventSubscriberInterface
{
    /**
     * @var \Netgen\BlockManager\Collection\Result\ResultLoaderInterface
     */
    protected $resultLoader;

    /**
     * @var \Netgen\BlockManager\API\Service\BlockService
     */
    protected $blockService;

    /**
     * @var int
     */
    protected $maxLimit;

    /**
     * @var array
     */
    protected $enabledContexts;

    /**
     * Constructor.
     *
     * @param \Netgen\BlockManager\Collection\Result\ResultLoaderInterface $resultLoader
     * @param \Netgen\BlockManager\API\Service\BlockService $blockService
     * @param int $maxLimit
     * @param array $enabledContexts
     */
    public function __construct(
        ResultLoaderInterface $resultLoader,
        BlockService $blockService,
        $maxLimit,
        array $enabledContexts = array()
    ) {
        $this->resultLoader = $resultLoader;
        $this->blockService = $blockService;
        $this->maxLimit = $maxLimit;
        $this->enabledContexts = $enabledContexts;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(BlockManagerEvents::RENDER_VIEW => 'onRenderView');
    }

    /**
     * Includes results built from all block collections, if specified so.
     *
     * @param \Netgen\BlockManager\Event\CollectViewParametersEvent $event
     */
    public function onRenderView(CollectViewParametersEvent $event)
    {
        $view = $event->getView();
        if (!$view instanceof BlockViewInterface) {
            return;
        }

        if (!in_array($view->getContext(), $this->enabledContexts, true)) {
            return;
        }

        $collections = array();

        $collectionReferences = $this->blockService->loadCollectionReferences($view->getBlock());
        foreach ($collectionReferences as $collectionReference) {
            $limit = $collectionReference->getLimit();
            if (empty($limit) || $limit > $this->maxLimit) {
                $limit = $this->maxLimit;
            }

            $collections[$collectionReference->getIdentifier()] = $this->resultLoader->load(
                $collectionReference->getCollection(),
                $collectionReference->getOffset(),
                $limit,
                $view->getContext() === ViewInterface::CONTEXT_API ?
                    ResultSet::INCLUDE_UNKNOWN_ITEMS :
                    0
            );
        }

        $event->addParameter('collections', $collections);
    }
}
