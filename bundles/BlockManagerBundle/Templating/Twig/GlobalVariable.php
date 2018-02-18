<?php

namespace Netgen\Bundle\BlockManagerBundle\Templating\Twig;

use Netgen\BlockManager\Layout\Resolver\LayoutResolverInterface;
use Netgen\BlockManager\View\View\LayoutViewInterface;
use Netgen\BlockManager\View\ViewBuilderInterface;
use Netgen\BlockManager\View\ViewInterface;
use Netgen\Bundle\BlockManagerBundle\Configuration\ConfigurationInterface;
use Netgen\Bundle\BlockManagerBundle\Templating\PageLayoutResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * This global variable injected into all templates serves two purposes.
 *
 * 1) It provides a convenient way to access the configuration object
 * 2) It provides the frontend templates with means to resolve layout
 *    and render its template.
 *
 * Resolving layouts with this global by calling `getLayoutTemplate()`
 * is only possible once per request for regular page, and once if
 * an exception happens. Subsequent calls will simply return the fallback
 * pagelayout.
 *
 * Reason for this is mainly exceptions that might happen in sub-requests,
 * while rendering blocks or block items. When an exception happens in
 * a sub-request, Symfony's ExceptionListener renders the exception,
 * but discards the rendered response and simply returns an empty response.
 * Since, usually, error templates will be Netgen Layouts enabled (meaning
 * they will extend `ngbm.layoutTemplate`), it might happen that sub-requests
 * call the layout resolving process again, which might overwrite the
 * already resolved layout due to different conditions, thus breaking
 * the main page, which still should be displayed in production environments
 * even if some of the sub-requests break.
 */
final class GlobalVariable
{
    /**
     * @var \Netgen\Bundle\BlockManagerBundle\Configuration\ConfigurationInterface
     */
    private $configuration;

    /**
     * @var \Netgen\BlockManager\Layout\Resolver\LayoutResolverInterface
     */
    private $layoutResolver;

    /**
     * @var \Netgen\Bundle\BlockManagerBundle\Templating\PageLayoutResolverInterface
     */
    private $pageLayoutResolver;

    /**
     * @var \Netgen\BlockManager\View\ViewBuilderInterface
     */
    private $viewBuilder;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $pageLayoutTemplate;

    public function __construct(
        ConfigurationInterface $configuration,
        LayoutResolverInterface $layoutResolver,
        PageLayoutResolverInterface $pageLayoutResolver,
        ViewBuilderInterface $viewBuilder,
        RequestStack $requestStack
    ) {
        $this->configuration = $configuration;
        $this->layoutResolver = $layoutResolver;
        $this->pageLayoutResolver = $pageLayoutResolver;
        $this->viewBuilder = $viewBuilder;
        $this->requestStack = $requestStack;
    }

    /**
     * Returns the currently resolved layout view.
     *
     * Since the regular Symfony exceptions are rendered only in sub-requests,
     * we can return the resolved non-error layout for master requests even if the
     * exception layout is resolved too (that might happen if an error or exception
     * happened inside a user implemented sub-request, like rendering a block
     * item).
     *
     * In other words, we return the resolved exception layout only in case of a
     * sub-request or in case of a master request if non-error layout is NOT resolved.
     * All other cases receive the non-error layout.
     *
     * @return \Netgen\BlockManager\View\View\LayoutViewInterface|bool
     */
    public function getLayoutView()
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $masterRequest = $this->requestStack->getMasterRequest();

        if ($masterRequest->attributes->has('ngbmExceptionLayoutView')) {
            if ($currentRequest !== $masterRequest || !$masterRequest->attributes->has('ngbmLayoutView')) {
                return $masterRequest->attributes->get('ngbmExceptionLayoutView');
            }
        }

        return $masterRequest->attributes->get('ngbmLayoutView');
    }

    /**
     * Returns the currently resolved layout.
     *
     * @return \Netgen\BlockManager\API\Values\Layout\Layout
     */
    public function getLayout()
    {
        $layoutView = $this->getLayoutView();
        if (!$layoutView instanceof LayoutViewInterface) {
            return null;
        }

        return $layoutView->getLayout();
    }

    /**
     * Returns the rule used to resolve the current layout.
     *
     * @return \Netgen\BlockManager\API\Values\LayoutResolver\Rule
     */
    public function getRule()
    {
        $layoutView = $this->getLayoutView();
        if (!$layoutView instanceof LayoutViewInterface) {
            return null;
        }

        return $layoutView->getParameter('rule');
    }

    /**
     * Returns the configuration object.
     *
     * @return \Netgen\Bundle\BlockManagerBundle\Configuration\ConfigurationInterface
     */
    public function getConfig()
    {
        return $this->configuration;
    }

    /**
     * Returns the pagelayout template.
     *
     * @return string
     */
    public function getPageLayoutTemplate()
    {
        if ($this->pageLayoutTemplate === null) {
            $this->pageLayoutTemplate = $this->pageLayoutResolver->resolvePageLayout();
        }

        return $this->pageLayoutTemplate;
    }

    /**
     * Returns the currently valid layout template, or base pagelayout if
     * no layout was resolved.
     *
     * @param string $context
     *
     * @return string
     */
    public function getLayoutTemplate($context = ViewInterface::CONTEXT_DEFAULT)
    {
        $layoutView = $this->buildLayoutView($context);
        if (!$layoutView instanceof LayoutViewInterface) {
            return $this->getPageLayoutTemplate();
        }

        return $layoutView->getTemplate();
    }

    /**
     * Resolves the used layout, based on current conditions.
     *
     * @param string $context
     *
     * @return \Netgen\BlockManager\View\ViewInterface
     */
    private function buildLayoutView($context = ViewInterface::CONTEXT_DEFAULT)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $masterRequest = $this->requestStack->getMasterRequest();

        if ($masterRequest->attributes->has('ngbmExceptionLayoutView')) {
            return null;
        }

        if (
            !$currentRequest->attributes->has('exception') &&
            $masterRequest->attributes->has('ngbmLayoutView')
        ) {
            return null;
        }

        $layoutView = false;

        $resolvedRules = $this->layoutResolver->resolveRules();
        if (!empty($resolvedRules)) {
            $layoutView = $this->viewBuilder->buildView(
                $resolvedRules[0]->getLayout(),
                $context,
                array(
                    'rule' => $resolvedRules[0],
                )
            );
        }

        $masterRequest->attributes->set(
            $currentRequest->attributes->has('exception') ?
                'ngbmExceptionLayoutView' :
                'ngbmLayoutView',
            $layoutView
        );

        return $layoutView;
    }
}
