<?php

defined('ABSPATH') || exit;

declare(strict_types=1);

namespace WPZylos\Framework\Validation\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Validation\Rules\RequiredRule;
use WPZylos\Framework\Validation\Rules\EmailRule;
use WPZylos\Framework\Validation\Rules\MinRule;
use WPZylos\Framework\Validation\Rules\MaxRule;
use WPZylos\Framework\Validation\Rules\NumericRule;
use WPZylos\Framework\Validation\Rules\AlphaRule;
use WPZylos\Framework\Validation\Rules\AlphaNumericRule;
use WPZylos\Framework\Validation\Rules\BetweenRule;
use WPZylos\Framework\Validation\Rules\InRule;
use WPZylos\Framework\Validation\Rules\UrlRule;
use WPZylos\Framework\Validation\Rules\RegexRule;
use WPZylos\Framework\Validation\Rules\ConfirmedRule;
use WPZylos\Framework\Validation\RuleInterface;

/**
 * Tests for individual validation rules.
 */
class RulesTest extends TestCase
{
    // --- RequiredRule ---

    public function testRequiredPassesWithValue(): void
    {
        $rule = new RequiredRule();
        $this->assertTrue($rule->passes('name', 'John', [], []));
    }

    public function testRequiredFailsWithEmpty(): void
    {
        $rule = new RequiredRule();
        $this->assertFalse($rule->passes('name', '', [], []));
    }

    public function testRequiredFailsWithNull(): void
    {
        $rule = new RequiredRule();
        $this->assertFalse($rule->passes('name', null, [], []));
    }

    public function testRequiredHasMessage(): void
    {
        $rule = new RequiredRule();
        $this->assertStringContainsString(':attribute', $rule->message());
    }

    // --- EmailRule ---

    public function testEmailPassesWithValidEmail(): void
    {
        $rule = new EmailRule();
        $this->assertTrue($rule->passes('email', 'test@example.com', [], []));
    }

    public function testEmailFailsWithInvalidEmail(): void
    {
        $rule = new EmailRule();
        $this->assertFalse($rule->passes('email', 'not-an-email', [], []));
    }

    // --- MinRule ---

    public function testMinPassesWithLongerString(): void
    {
        $rule = new MinRule();
        $this->assertTrue($rule->passes('name', 'hello', ['5'], []));
    }

    public function testMinFailsWithShorterString(): void
    {
        $rule = new MinRule();
        $this->assertFalse($rule->passes('name', 'hi', ['5'], []));
    }

    public function testMinPassesWithNumericValue(): void
    {
        $rule = new MinRule();
        $this->assertTrue($rule->passes('age', 18, ['18'], []));
    }

    // --- MaxRule ---

    public function testMaxPassesWithShorterString(): void
    {
        $rule = new MaxRule();
        $this->assertTrue($rule->passes('name', 'hi', ['5'], []));
    }

    public function testMaxFailsWithLongerString(): void
    {
        $rule = new MaxRule();
        $this->assertFalse($rule->passes('name', 'hello world', ['5'], []));
    }

    // --- NumericRule ---

    public function testNumericPassesWithNumber(): void
    {
        $rule = new NumericRule();
        $this->assertTrue($rule->passes('age', '42', [], []));
    }

    public function testNumericFailsWithString(): void
    {
        $rule = new NumericRule();
        $this->assertFalse($rule->passes('age', 'abc', [], []));
    }

    // --- AlphaRule ---

    public function testAlphaPassesWithLetters(): void
    {
        $rule = new AlphaRule();
        $this->assertTrue($rule->passes('name', 'John', [], []));
    }

    public function testAlphaFailsWithNumbers(): void
    {
        $rule = new AlphaRule();
        $this->assertFalse($rule->passes('name', 'John123', [], []));
    }

    // --- AlphaNumericRule ---

    public function testAlphaNumericPassesWithMixed(): void
    {
        $rule = new AlphaNumericRule();
        $this->assertTrue($rule->passes('code', 'abc123', [], []));
    }

    public function testAlphaNumericFailsWithSpecialChars(): void
    {
        $rule = new AlphaNumericRule();
        $this->assertFalse($rule->passes('code', 'abc@123', [], []));
    }

    // --- BetweenRule ---

    public function testBetweenPassesWithinRange(): void
    {
        $rule = new BetweenRule();
        $this->assertTrue($rule->passes('age', 25, ['18', '65'], []));
    }

    public function testBetweenFailsOutOfRange(): void
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->passes('age', 10, ['18', '65'], []));
    }

    // --- InRule ---

    public function testInPassesWithValidValue(): void
    {
        $rule = new InRule();
        $this->assertTrue($rule->passes('status', 'active', ['active', 'inactive'], []));
    }

    public function testInFailsWithInvalidValue(): void
    {
        $rule = new InRule();
        $this->assertFalse($rule->passes('status', 'deleted', ['active', 'inactive'], []));
    }

    // --- UrlRule ---

    public function testUrlPassesWithValidUrl(): void
    {
        $rule = new UrlRule();
        $this->assertTrue($rule->passes('website', 'https://example.com', [], []));
    }

    public function testUrlFailsWithInvalidUrl(): void
    {
        $rule = new UrlRule();
        $this->assertFalse($rule->passes('website', 'not-a-url', [], []));
    }

    // --- RegexRule ---

    public function testRegexPassesWithMatch(): void
    {
        $rule = new RegexRule();
        $this->assertTrue($rule->passes('code', 'ABC-123', ['/^[A-Z]+-\d+$/'], []));
    }

    public function testRegexFailsWithoutMatch(): void
    {
        $rule = new RegexRule();
        $this->assertFalse($rule->passes('code', 'abc', ['/^[A-Z]+-\d+$/'], []));
    }

    // --- ConfirmedRule ---

    public function testConfirmedPassesWithMatchingField(): void
    {
        $rule = new ConfirmedRule();
        $this->assertTrue($rule->passes(
            'password', 'secret123', [],
            ['password' => 'secret123', 'password_confirmation' => 'secret123']
        ));
    }

    public function testConfirmedFailsWithMismatch(): void
    {
        $rule = new ConfirmedRule();
        $this->assertFalse($rule->passes(
            'password', 'secret123', [],
            ['password' => 'secret123', 'password_confirmation' => 'different']
        ));
    }

    // --- All rules implement RuleInterface ---

    /**
     * @dataProvider ruleClassesProvider
     */
    public function testAllRulesImplementInterface(string $ruleClass): void
    {
        $rule = new $ruleClass();
        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @dataProvider ruleClassesProvider
     */
    public function testAllRulesHaveMessage(string $ruleClass): void
    {
        $rule = new $ruleClass();
        $this->assertNotEmpty($rule->message());
    }

    public function ruleClassesProvider(): array
    {
        return [
            'required' => [RequiredRule::class],
            'email' => [EmailRule::class],
            'min' => [MinRule::class],
            'max' => [MaxRule::class],
            'numeric' => [NumericRule::class],
            'alpha' => [AlphaRule::class],
            'alphanumeric' => [AlphaNumericRule::class],
            'between' => [BetweenRule::class],
            'in' => [InRule::class],
            'url' => [UrlRule::class],
            'regex' => [RegexRule::class],
            'confirmed' => [ConfirmedRule::class],
        ];
    }
}
