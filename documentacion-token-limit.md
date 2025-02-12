# Token Limit Module Documentation - Gemini Lite Laravel

## Validation Summary
The current documentation has been validated and tested. Below is an enhanced version with clarifications and verified examples.

## Initial Setup

### 1. Publishing Migrations and Seeders
```bash
php artisan vendor:publish --tag="geminilite-limit-tokes"
php artisan migrate
```

### 2. User Model Configuration
```php
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\HasGeminiRoles;

class User extends Authenticatable
{
    use HasGeminiRoles;
    // ...rest of the model
}
```

## Main Features

### 1. Role Management

#### Role Assignment
```php
// By role name (Recommended)
$user->assignGeminiRole('limited_user');
$user->assignGeminiRole('premium_user');

// By role ID
$user->assignGeminiRole(1); // premium_user
$user->assignGeminiRole(2); // limited_user
```

### 2. Access Control

#### Permission Verification
```php
// Checks token limits and requests
$canMakeRequest = $user->canMakeRequestToGemini();

// Checks only active status
$isActive = $user->isActiveInGemini();
```

⚠️ **Important**: 
- `canMakeRequestToGemini()`: Verifies daily/monthly token limits and request counts
- `isActiveInGemini()`: Only verifies if the user is active in the system

### 3. Usage Tracking

#### Usage Update
```php
// 1. Count prompt tokens
$tokens = GeminiTokenCount::coutTextTokens($prompt);

// 2. Check if request can be made
if (!$user->canMakeRequestToGemini()) {
    return response()->json([
        "success" => false,
        "message" => "Token or request limit exceeded",
    ], 403);
}

// 3. Make Gemini request
$gemini = Gemini::newChat();
$response = $gemini->newPrompt($prompt);

// 4. Update usage tracking
$user->updateUsageTracking($tokens);

// 5. Log the request (optional)
$user->storeGeminiRequest(
    requestType: "chat",
    consumedTokens: $tokens,
    requestSuccessful: true,
    requestData: ["prompt" => $prompt],
    responseData: ["response" => $response]
);
```

### 4. Predefined Roles

| Role          | Daily Limit | Monthly Limit | Daily Tokens | Monthly Tokens |
|---------------|-------------|---------------|--------------|----------------|
| premium_user  | 1,000       | 30,000        | 1,000,000    | 30,000,000     |
| limited_user  | 100         | 3,000         | 300,000      | 9,000,000      |

## Complete Implementation Example

```php
class AIChatController extends Controller
{
    public function chat(Request $request)
    {
        $user = auth()->user();
        $prompt = $request->input('prompt');
        
        // 1. Check tokens
        $tokens = GeminiTokenCount::coutTextTokens($prompt);
        
        // 2. Verify permissions
        if (!$user->canMakeRequestToGemini()) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached your token or request limit'
            ], 403);
        }
        
        try {
            // 3. Make request
            $gemini = Gemini::newChat();
            $response = $gemini->newPrompt($prompt);
            
            // 4. Update usage
            $user->updateUsageTracking($tokens);
            
            // 5. Log request
            $user->storeGeminiRequest(
                requestType: "chat",
                consumedTokens: $tokens,
                requestSuccessful: true,
                requestData: ["prompt" => $prompt],
                responseData: ["response" => $response]
            );
            
            return response()->json([
                'success' => true,
                'response' => $response
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing request'
            ], 500);
        }
    }
}
```

## Best Practices

1. **Prior Verification**: Always check `canMakeRequestToGemini()` before making requests.
2. **Token Counting**: Count tokens before making the request to avoid exceeding limits.
3. **Usage Logging**: Keep track of requests using `storeGeminiRequest`.
4. **Error Handling**: Implement try-catch blocks to handle API errors.

## Database Tables

### gemini_lite_roles
- Stores role configurations
- Request and token limits

### gemini_lite_role_assignments
- User role assignments
- Assignment active status

### gemini_lite_usage
- User usage tracking
- Daily and monthly counters

### gemini_lite_request_logs
- Detailed request history
- Request and response data