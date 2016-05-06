<?php

namespace Netgen\BlockManager\Tests\LayoutResolver;

use Netgen\BlockManager\LayoutResolver\Condition;
use Netgen\BlockManager\LayoutResolver\ConditionMatcher\RegistryInterface as ConditionMatcherRegistryInterface;
use Netgen\BlockManager\LayoutResolver\TargetBuilder\RegistryInterface as TargetBuilderRegistryInterface;
use Netgen\BlockManager\LayoutResolver\RuleLoader\RuleLoaderInterface;
use Netgen\BlockManager\LayoutResolver\Rule;
use Netgen\BlockManager\LayoutResolver\LayoutResolver;
use Netgen\BlockManager\Tests\LayoutResolver\Stubs\ConditionMatcher;
use Netgen\BlockManager\Tests\LayoutResolver\Stubs\Target as TargetStub;
use Netgen\BlockManager\Tests\LayoutResolver\Stubs\TargetBuilder;
use Netgen\BlockManager\Tests\LayoutResolver\Stubs\TargetBuilderReturnsFalse;

class LayoutResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $targetBuilderRegistryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $conditionMatcherRegistryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ruleLoaderMock;

    /**
     * @var \Netgen\BlockManager\LayoutResolver\LayoutResolverInterface
     */
    protected $layoutResolver;

    public function setUp()
    {
        $this->targetBuilderRegistryMock = $this->getMock(
            TargetBuilderRegistryInterface::class
        );

        $this->conditionMatcherRegistryMock = $this->getMock(
            ConditionMatcherRegistryInterface::class
        );

        $this->ruleLoaderMock = $this->getMock(
            RuleLoaderInterface::class
        );

        $this->layoutResolver = new LayoutResolver(
            $this->targetBuilderRegistryMock,
            $this->conditionMatcherRegistryMock,
            $this->ruleLoaderMock
        );
    }

    /**
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::__construct
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::resolveLayout
     */
    public function testResolveLayout()
    {
        $targetBuilder1 = new TargetBuilder(array(42));
        $targetBuilder2 = new TargetBuilder(array(84));
        $target1 = new TargetStub(array(42));
        $target2 = new TargetStub(array(84));
        $rule2 = new Rule(84, $target2);

        $this->targetBuilderRegistryMock
            ->expects($this->once())
            ->method('getTargetBuilders')
            ->will($this->returnValue(array($targetBuilder1, $targetBuilder2)));

        $this->ruleLoaderMock
            ->expects($this->at(0))
            ->method('loadRules')
            ->with($this->equalTo($target1))
            ->will($this->returnValue(array()));

        $this->ruleLoaderMock
            ->expects($this->at(1))
            ->method('loadRules')
            ->with($this->equalTo($target2))
            ->will($this->returnValue(array($rule2)));

        self::assertEquals($rule2, $this->layoutResolver->resolveLayout());
    }

    /**
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::__construct
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::resolveLayout
     */
    public function testResolveLayoutWithNoTarget()
    {
        $targetBuilder1 = new TargetBuilderReturnsFalse();
        $targetBuilder2 = new TargetBuilder(array(84));
        $target2 = new TargetStub(array(84));
        $rule2 = new Rule(84, $target2);

        $this->targetBuilderRegistryMock
            ->expects($this->once())
            ->method('getTargetBuilders')
            ->will($this->returnValue(array($targetBuilder1, $targetBuilder2)));

        $this->ruleLoaderMock
            ->expects($this->at(0))
            ->method('loadRules')
            ->with($this->equalTo($target2))
            ->will($this->returnValue(array($rule2)));

        self::assertEquals($rule2, $this->layoutResolver->resolveLayout());
    }

    /**
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::__construct
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::resolveLayout
     */
    public function testResolveLayoutWithNoMatchingTargets()
    {
        $targetBuilder1 = new TargetBuilder(array(42));
        $targetBuilder2 = new TargetBuilder(array(84));
        $target1 = new TargetStub(array(42));
        $target2 = new TargetStub(array(84));

        $this->targetBuilderRegistryMock
            ->expects($this->once())
            ->method('getTargetBuilders')
            ->will($this->returnValue(array($targetBuilder1, $targetBuilder2)));

        $this->ruleLoaderMock
            ->expects($this->at(0))
            ->method('loadRules')
            ->with($this->equalTo($target1))
            ->will($this->returnValue(array()));

        $this->ruleLoaderMock
            ->expects($this->at(1))
            ->method('loadRules')
            ->with($this->equalTo($target2))
            ->will($this->returnValue(array()));

        self::assertFalse($this->layoutResolver->resolveLayout());
    }

    /**
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::__construct
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::resolveLayoutForTarget
     */
    public function testResolveLayoutForTarget()
    {
        $target = new TargetStub(array(42));
        $rule = new Rule(42, $target);

        $this->ruleLoaderMock
            ->expects($this->once())
            ->method('loadRules')
            ->with($this->equalTo($target))
            ->will($this->returnValue(array($rule)));

        self::assertEquals($rule, $this->layoutResolver->resolveLayoutForTarget($target));
    }

    /**
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::__construct
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::resolveLayoutForTarget
     */
    public function testResolveFirstLayoutForTargetWithMoreThanOneMatchingRule()
    {
        $target = new TargetStub(array(42));
        $rule1 = new Rule(42, $target);
        $rule2 = new Rule(84, $target);

        $this->ruleLoaderMock
            ->expects($this->once())
            ->method('loadRules')
            ->with($this->equalTo($target))
            ->will($this->returnValue(array($rule1, $rule2)));

        self::assertEquals($rule1, $this->layoutResolver->resolveLayoutForTarget($target));
    }

    /**
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::__construct
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::resolveLayoutForTarget
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::matchConditions
     *
     * @param array $matches
     * @param int $layoutId
     *
     * @dataProvider resolveLayoutForTargetWithRuleConditionsProvider
     */
    public function testResolveLayoutForTargetWithRuleConditions(array $matches, $layoutId)
    {
        $target = new TargetStub(array('value'));

        $conditions = array();
        $matchFailed = false;
        foreach ($matches as $index => $match) {
            $conditions[] = new Condition('condition', array('value'));

            if (!$matchFailed) {
                $this->conditionMatcherRegistryMock
                    ->expects($this->at($index))
                    ->method('getConditionMatcher')
                    ->will($this->returnValue(new ConditionMatcher($match)));
            }

            $matchFailed = !$matchFailed && !$match;
        }

        $rule = new Rule($layoutId, $target, $conditions);

        $this->ruleLoaderMock
            ->expects($this->once())
            ->method('loadRules')
            ->with($this->equalTo($target))
            ->will($this->returnValue(array($rule)));

        self::assertEquals($layoutId !== false ? $rule : false, $this->layoutResolver->resolveLayoutForTarget($target));
    }

    /**
     * Data provider for {@link self::testResolveLayoutForTargetWithRuleConditions}.
     *
     * @return array
     */
    public function resolveLayoutForTargetWithRuleConditionsProvider()
    {
        return array(
            array(array(true), 42),
            array(array(false), false),
            array(array(true, false), false),
            array(array(false, true), false),
            array(array(false, false), false),
            array(array(true, true), 42),
        );
    }

    /**
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::__construct
     * @covers \Netgen\BlockManager\LayoutResolver\LayoutResolver::resolveLayoutForTarget
     */
    public function testResolveLayoutForTargetWithNoRules()
    {
        $target = new TargetStub(array(42));

        $this->ruleLoaderMock
            ->expects($this->once())
            ->method('loadRules')
            ->with($this->equalTo($target))
            ->will($this->returnValue(array()));

        self::assertFalse($this->layoutResolver->resolveLayoutForTarget($target));
    }
}
