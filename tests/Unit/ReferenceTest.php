<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidReferenceArgument;
use OpenTracing\NoopSpanContext;
use OpenTracing\Reference;
use OpenTracing\Span;
use OpenTracing\SpanContext;
use PHPUnit_Framework_TestCase;

/**
 * @covers Reference
 */
final class ReferenceTest extends PHPUnit_Framework_TestCase
{
    const REFERENCE_TYPE = 'ref_type';

    public function testCreateAReferenceFailsOnInvalidContext()
    {
        $context = 'invalid_context';
        $this->setExpectedException(
            InvalidReferenceArgument::class,
            'Reference expects \\' . Span::class . ' or \\' . SpanContext::class . ' as context, got string'
        );
        Reference::create('child_of', $context);
    }

    public function testCreateAReferenceFailsOnEmptyType()
    {
        $context = new NoopSpanContext();
        $this->setExpectedException(
            InvalidReferenceArgument::class,
            'Reference type can not be an empty string'
        );
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
