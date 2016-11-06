<?php

namespace Netgen\Bundle\BlockManagerBundle\Templating\Twig\TokenParser;

use Netgen\Bundle\BlockManagerBundle\Templating\Twig\Node\RenderBlock as RenderBlockNode;
use Twig_TokenParser;
use Twig_Error_Syntax;
use Twig_Token;

class RenderBlock extends Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token
     *
     * @throws \Twig_Error_Syntax
     *
     * @return \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Node\RenderBlock
     */
    public function parse(Twig_Token $token)
    {
        $stream = $this->parser->getStream();

        $context = null;
        $block = $this->parser->getExpressionParser()->parseExpression();

        while (!$stream->test(Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->test(Twig_Token::NAME_TYPE, 'context')) {
                $stream->next();
                $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
                $context = $this->parser->getExpressionParser()->parseExpression();

                continue;
            }

            $token = $stream->getCurrent();
            throw new Twig_Error_Syntax(
                sprintf(
                    'Unexpected token "%s" of value "%s".',
                    Twig_Token::typeToEnglish($token->getType()),
                    $token->getValue()
                ),
                $token->getLine(),
                $stream->getSourceContext()->getName()
            );
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new RenderBlockNode($block, $context, $token->getLine(), $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string
     */
    public function getTag()
    {
        return 'ngbm_render_block';
    }
}
