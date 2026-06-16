# wpzylos/validation

Data validation and form request layer for WordPress plugins. Provides a fluent `Validator`, abstract `FormRequest` with built-in sanitization, structured error handling via `MessageBag`, and extensibility through custom rules.

**Namespace:** `WPZylos\Framework\Validation`

---

## Features

- **Validator** - Validate data arrays against rules with pipe syntax or array syntax
- **14 Built-in Rules** - `required`, `string`, `integer`/`int`, `numeric`, `boolean`, `array`, `email`, `url`, `min`, `max`, `in`, `regex`, `nullable`
- **12 Standalone Rule Classes** - `RequiredRule`, `EmailRule`, `UrlRule`, `NumericRule`, `AlphaRule`, `AlphaNumericRule`, `MinRule`, `MaxRule`, `BetweenRule`, `InRule`, `ConfirmedRule`, `RegexRule`
- **FormRequest** - Abstract class combining authorization, sanitization, and validation
- **10 Sanitizer Types** - `text`, `textarea`, `html`, `email`, `url`, `int`, `absint`, `float`, `bool`, `slug`, `key`
- **MessageBag** - Structured error collection with per-field access
- **Custom Rules** - Extend validation via `RuleInterface`
- **i18n Support** - Optional `Translator` integration for localized messages
- **Custom Messages** - Field-specific and rule-level message overrides

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.1+ |
| WordPress | 6.0+ |
| wpzylos/http | Required for `FormRequest` |

---

## Installation

```bash
composer require wpzylos/validation
```

---

## Quick Start

### Basic Validation

```php
use WPZylos\Framework\Validation\Validator;

$validator = new Validator(
    data:  ['email' => 'invalid', 'name' => ''],
    rules: ['email' => 'required|email', 'name' => 'required|min:2'],
);

if ($validator->fails()) {
    $errors = $validator->errors();
    echo $errors->first('email');  // "The email field must be a valid email address."
    echo $errors->first('name');   // "The name field is required."
}

// Get validated data (throws ValidationException if invalid)
$data = $validator->validated();
```

### FormRequest

```php
use WPZylos\Framework\Validation\FormRequest;

class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'   => 'required|string|min:3|max:200',
            'content' => 'required|string',
            'status'  => 'required|in:draft,published',
        ];
    }

    public function sanitize(): array
    {
        return [
            'title'   => 'text',
            'content' => 'html',
            'status'  => 'key',
        ];
    }
}
```

---

## Documentation

- **[Usage Guide](docs/usage.md)** - Comprehensive guide covering validation, rules, FormRequest, custom rules, and error handling.
- **[API Reference](docs/api-reference.md)** - Full method-by-method reference for every class and interface.

---

## Support the Project

- [GitHub Sponsors](https://github.com/sponsors/KYNetCode)
- [PayPal Donate](https://www.paypal.com/donate/?hosted_button_id=66U4L3HG4TLCC)

---

## License

MIT License. See [LICENSE](LICENSE) for details.
