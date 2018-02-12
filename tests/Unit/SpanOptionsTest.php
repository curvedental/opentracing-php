<?php

namespace OpenTracingTests\Unit;

use OpenTracing\NoopSpanContext;
use OpenTracing\SpanOptions;
use OpenTracing\Reference;
use PHPUnit_Framework_TestCase;

/**
 * @covers SpanOptions
 */
final class SpanOptionsTest extends PHPUnit_Framework_TestCase
{
    const REFERENCE_TYPE = 'a_reference_type';

    /**
     * @expectedException \OpenTracing\Exceptions\InvalidSpanOption
     */
    public function testSpanOptionsCanNotBeCreatedDueToInvalidOption()
    {
        SpanOptions::create([
            'unknown_option' => 'value'
        ]);
    }

    /**
     * @expectedException \OpenTracing\Exceptions\InvalidSpanOption
     */
    public function testSpanOptionsWithInvalidCloseOnFinishOption()
    {
        SpanOptions::create([
            'close_span_on_finish' => 'value'
        ]);
    }

    /**
     * @expectedException \OpenTracing\Exceptions\InvalidSpanOption
     */
    public function testSpanOptionsCanNotBeCreatedBecauseInvalidStartTime()
    {
        SpanOptions::create([
            'start_time' => 'abc'
        ]);
    }

    /** @dataProvider validStartTime */
    public function testSpanOptionsCanBeCreatedBecauseWithValidStartTime($startTime)
    {
        $spanOptions = SpanOptions::create([
            'start_time' => $startTime
        ]);

        $this->assertEquals($spanOptions->getStartTime(), $startTime);
    }

    public function validStartTime()
    {
        return [
            [new \DateTime()],
            ['1499355363'],
            [1499355363],
            [1499355363.123456]
        ];
    }

    public function testSpanOptionsCanBeCreatedWithValidReference()
    {
        $context = NoopSpanContext::create();

        $options = [
            'references' => Reference::create(self::REFERENCE_TYPE, $context),
        ];

        $spanOptions = SpanOptions::create($options);
        $references = $spanOptions->getReferences()[0];

        $this->assertTrue($references->isType(self::REFERENCE_TYPE));
        $this->assertSame($context, $references->getContext());
    }

    public function testSpanOptionsDefaultCloseOnFinishValue()
    {
        $options = SpanOptions::create([]);

        $this->assertTrue($options->getCloseSpanOnFinish());
    }

    public function testSpanOptionsWithValidCloseOnFinishValue()
    {
        $options = SpanOptions::create([
            'close_span_on_finish' => false,
        ]);

        $this->assertFalse($options->getCloseSpanOnFinish());
    }
}
