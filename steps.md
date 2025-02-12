# Setting up Gemini Lite Laravel Dev Environment

## 1. Create new Laravel project
```bash
composer create-project laravel/laravel my-gemini-test
cd my-gemini-test
```

## 2. Manual Package Installation
1. Create directory for the package:
```bash
mkdir -p vendor/liteopensource/gemini-lite-laravel
```

2. Copy the development version of the package from gemini-lite-laravel-dev repository into this directory.

3. Update composer.json to use the local package:
```json
{
    "require": {
        "liteopensource/gemini-lite-laravel": "*"
    },
    "repositories": [
        {
            "type": "path",
            "url": "./vendor/liteopensource/gemini-lite-laravel"
        }
    ]
}
```

## 3. Configure the Project
1. Run composer update:
```bash
composer update
```

2. Publish the configuration:
```bash
php artisan vendor:publish --tag="geminilite-config"
php artisan vendor:publish --tag="geminilite-limit-tokes"
```

3. Add GEMINILITE_SECRET_API_KEY to .env:
```
GEMINILITE_SECRET_API_KEY=your_api_key_here
```

4. Run migrations:
```bash
php artisan migrate
```

## Testing the Token Limit Feature
1. Add the HasGeminiRoles trait to your User model:
```php
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\HasGeminiRoles;

class User extends Authenticatable
{
    use HasGeminiRoles;
    // ...existing code...
}
```

2. Create a test user and assign roles using the examples from the documentation.
