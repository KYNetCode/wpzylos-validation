<?php

defined('ABSPATH') || exit;

declare(strict_types=1);

namespace WPZylos\Framework\Validation\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Validation\FormRequest;
use WPZylos\Framework\Validation\ValidationException;
use WPZylos\Framework\Validation\ValidationServiceProvider;

/**
 * Tests for validation infrastructure classes.
 */
class ValidationInfrastructureTest extends TestCase
{
    public function testFormRequestClassExists(): void
    {
        $this->assertTrue(class_exists(FormRequest::class));
    }

    public function testValidationExceptionClassExists(): void
    {
        $this->assertTrue(class_exists(ValidationException::class));
    }

    public function testValidationExceptionIsThrowable(): void
    {
        $exception = new ValidationException(new \WPZylos\Framework\Validation\MessageBag());
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testValidationServiceProviderIsInstantiable(): void
    {
        $provider = new ValidationServiceProvider();
        $this->assertInstanceOf(ValidationServiceProvider::class, $provider);
    }
}
