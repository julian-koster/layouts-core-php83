<?php

namespace Netgen\Bundle\BlockManagerBundle\Controller;

use Exception;
use Netgen\BlockManager\API\Values\Block\Block;
use Netgen\BlockManager\View\ViewInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BlockController extends Controller
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $debug = false;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * Sets if debug is enabled or not.
     *
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = (bool) $debug;
    }

    /**
     * Renders the provided block. Used by ESI rendering strategy, so if rendering fails,
     * we log an error and just return an empty response in order not to crash the page.
     *
     * @param \Netgen\BlockManager\API\Values\Block\Block $block
     * @param string $context
     *
     * @throws \Exception If rendering fails
     *
     * @return \Netgen\BlockManager\View\View\BlockViewInterface|\Symfony\Component\HttpFoundation\Response
     */
    public function viewBlock(Block $block, $context = ViewInterface::CONTEXT_DEFAULT)
    {
        try {
            return $this->buildView($block, $context);
        } catch (Exception $e) {
            $errorMessage = sprintf('Error rendering a block with ID %d', $block->getId());

            return new Response($this->handleException($e, $errorMessage));
        }
    }

    /**
     * Renders the provided block with the AJAX view.
     *
     * Block rendered with AJAX view is always rendered with a collection
     * which is injected into a block at a certain page.
     *
     * Paging itself of the collection is not handled here, but rather in
     * an event listener triggering when the block is rendered.
     *
     * @param \Netgen\BlockManager\API\Values\Block\Block $block
     * @param string $collectionIdentifier
     * @param string $context
     *
     * @throws \Exception If rendering fails
     *
     * @return \Netgen\BlockManager\View\View\BlockViewInterface|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAjaxBlock(Block $block, $collectionIdentifier, $context = ViewInterface::CONTEXT_AJAX)
    {
        try {
            return $this->buildView(
                $block,
                $context,
                array(
                    'collection_identifier' => $collectionIdentifier,
                )
            );
        } catch (Exception $e) {
            $errorMessage = sprintf(
                'Error rendering an AJAX block with ID %d and collection %s',
                $block->getId(),
                $collectionIdentifier
            );

            return new JsonResponse($this->handleException($e, $errorMessage));
        }
    }

    protected function checkPermissions()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_ANONYMOUSLY');
    }

    /**
     * Handles the exception based on provided debug flag.
     *
     * @param \Exception $exception
     * @param string $errorMessage
     *
     * @todo Refactor out to separate service
     *
     * @throws \Exception
     */
    private function handleException(Exception $exception, $errorMessage)
    {
        $this->logger->error($errorMessage . ': ' . $exception->getMessage());

        if ($this->debug) {
            throw $exception;
        }
    }
}
