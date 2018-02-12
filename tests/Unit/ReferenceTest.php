<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidReferenceArgument;
use OpenTracing\NoopSpanContext;
use OpenTracing\Reference;
use PHPUnit_Framework_TestCase;
use InvalidArgumentException;

/**
 * @covers Reference
 */
final class ReferenceTest extends PHPUnit_Framework_TestCase
{
    const REFERENCE_TYPE = 'ref_type';

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Reference expects \OpenTracing\Span or \OpenTracing\SpanContext as context, got string
     */
    public function testCreateAReferenceFailsOnInvalidContext()
    {
        $context = 'invalid_context';
        Reference::create('child_of', $context);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Reference type can not be an empty string
     */
    public function testCreateAReferenceFailsOnEmptyType()
    {
        $context = new NoopSpanContext();
        Reference::create('', $context);
    }

    public function testAReferenceCanBeCreatedAsACustomType()
    {
        $context = new NoopSpanContext();
        $reference = Reference::create(self::REFERENCE_TYPE, $context);
        $this->assertSame($context, $reference->getContext());
        $this->assertTrue($reference->isType(self::REFERENCE_TYPE));
    }
}
