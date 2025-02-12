Skip to content
Navigation Menu
LiteOpenSource
GeminiLite-Laravel

Type / to search
Code
Issues
Pull requests
Actions
Projects
Security
Insights
Settings
Owner avatar
GeminiLite-Laravel
Public
LiteOpenSource/GeminiLite-Laravel
Go to file
t
This branch is 1 commit ahead of, 1 commit behind main.
Name		
edgarml01
edgarml01
Adding documentation about Token Limit feature
1914f8c
 · 
15 hours ago
config
Version 0.0.1
4 months ago
src
Livewire support for upload files | Documentation added for v0.0.3
last month
.gitignore
Version 0.0.1
4 months ago
README.md
Adding documentation about Token Limit feature
15 hours ago
composer.json
Livewire support for upload files | Documentation added for v0.0.3
last month
Repository files navigation
README
Gemini Lite for Laravel - Documentation
🦸🏽‍♀️🛠️🪚⚙️🦸🏽 YOU CAN CONTRIBUTE TO THIS PROJECT, CONTACT ME TO: jose.lopez.lara.cto@gmail.com 🦸🏽‍♀️🛠️🪚⚙️🦸🏽

Latest Stable Version Total Downloads Stars License

Why use Gemini lite instead other open source Gemini Sdk?
Thanks to its minimalist syntax based on Facades, you can integrate AI functionalities effortlessly. Soon, it will include a ✨token and request limit management system inspired by Laravel Permission✨, perfect for streamlining monetizable projects. Moreover, its support is guaranteed until 2026, as it will be used in two projects set to be launched into production this year.Support the project and help extend its lifespan. If you'd like to contribute, feel free to contact me.

Features
Feature status:

🟢 Feature added
🟡 Feature in progress
🔴 Feature doesn't started
🟣 Feature doesn't planet (If you want contribute with this, contact me)
In progress	Progres Status
Text prompt support	🟢
Text prompt with file(Iamge, pdf, etc) support	🟢
Chat history support	🟢
Change model config in runtime	🟢
Change between Gemini models	🟢
JSON mode support	🟢
Easy upload file to get uri and mime type	🟢
Easy get current gemini config function	🟢
Easy get chat history of current chat instance	🔴
✨ Limit tokens and request support ✨	🟡
Tokens counter before to do prompt	🟡
Gemini flash 2.0 beta	🟡
Automatic unite testing	🟡
Embedding support	🔴
Image generator support	🟣
Table of Contents
Get started

Requeriments
Installation
Configuration

GeminiService

Creating a New Chat
Sending Prompts
Changing Model Configuration
Using JSON Mode
Changing Gemini Model
Getting Current Model Configuration
UploadFileToGeminiService

Processing Files from a Path
Processing Uploaded Files
Token count

Token Limit

Assign Role
Can make Reuqest?
IsActive
Update usage
Store gemini request
Examples

Text-Based Chat
Image-Based Chat
Changing Configuration at Runtime
JSON Mode Chat
Changing Gemini Model
Getting Current Model Configuration
Count the number of tokens
Check if the user can make a request to Gemini.
Check if the user is active in Gemini.
Update the usage tracking for the user
Assign roles to users
Log the request made by the user
License

Get Started
Requeriments
You have to verify have added in your project:

php: Minimum version ^8.0
PostgreSQL
guzzlehttp/guzzle: Minimum version ^7.0
illuminate/console: Minimum version ^9.0
illuminate/database: Minimum version ^9.0
illuminate/http: Minimum version ^9.0
illuminate/support: Minimum version ^9.0
Installation
To install the Gemini Lite for Laravel package, use composer require liteopensource/gemini-lite-laravel or add the following line to your composer.json

"require": {
    "liteopensource/gemini-lite-laravel": "^0.0.3"
}
Then run: composer update

Configuration
Publish your config file geminilite.php using:

php artisan vendor:publish --tag="geminilite-config"

Add your Gemini API key to your .env file:

GEMINILITE_SECRET_API_KEY=your_api_key_here

GeminiService
GeminiService provides methods to interact with the Gemini AI model.

Creating a New Chat
Example of starting a new chat session:

use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;

$chat = Gemini::newChat();
Sending Prompts
To send a prompt to the Gemini model:

$response = $chat->newPrompt("Your prompt here");
Changing Model Configuration
You can modify the model configuration at runtime:

$chat->setGeminiModelConfig($temperature, $topK, $topP, $maxOutputTokens, $returnMimeType);
Using JSON Mode
$responseSchema = [
    // Your JSON schema here
];

$chat->setGeminiModelConfig(
    temperature: 1,
    topK: 40,
    topP: 0.95,
    maxOutputTokens: 8192,
    responseMimeType: 'application/json',
    responseSchema: $responseSchema
);
Changing Gemini Model
You can switch between different Gemini models, the curren model avaibles are:

gemini-1.5-flash
gemini-1.5-flash-002
gemini-1.5-flash-8b
gemini-1.5-pro
gemini-1.5-pro-002
$chat->changeGeminiModel("gemini-1.5-pro-002");
Getting Current Model Configuration
You can retrieve the current configuration of the Gemini model:

$currentConfig = $chat->getGeminiModelConfig();
UploadFileToGeminiService
This service allows you to upload files for processing by Gemini.

Processing Files from a Path
To process a file from a local path:

use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;

$filePath = storage_path('your/file/path.file');
$fileProcessed = UploadFileToGemini::processFileFromPath($filePath);

$uri = $fileProcessed->getUri();
$mimeType = $fileProcessed->getMimeType();
Processing Uploaded Files
This is really useful when you work with Livewire and using use WithFileUploads;

To process an uploaded file:

use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;

$file = $request->file($yourFile);
$fileProcessed = UploadFileToGemini::processFileFromUpload($file);

$uri = $fileProcessed->getUri();
$mimeType = $fileProcessed->getMimeType();
GeminiTokenCountService
This service is responsible for counting the tokens consumed by a text prompt. It returns an integer indicating the number of tokens consumed by the text prompt.

$tokens = GeminiTokenCount::coutTextTokens("Input text");
Token Limit
Token limit is independent of GeminiChat so the use of this feature is not integrated into the use of Gemini

This feature is intended for controlling user token consumption. It uses tables in the database to control usage. Therefore, it is important to publish the respective seeders for its operation.

Command to publish seeder and tables:

php artisan vendor:publish --tag="geminilite-limit-tokes"
To use this feature you need to add the HasGeminiRoles trait to the model user

use LiteOpenSource\GeminiLiteLaravel\Src\Traits\HasGeminiRoles;

class User extends Authenticatable
{
    use  HasGeminiRoles;

}
AssignRole
$testUser = User::create([
    'name' => 'John',
    'email' => 'test@example.com',
    'password'=> Hash::make("password"),
]);
/*
You can assign the role by the 
index or name of the role in the database 

$testUser2->assignGeminiRole('limited_user');

*/
$testUser->assignGeminiRole(1);
Can make request?
This function is for check if the user can make request to Gemini according to the established limits. The function returs a boolean value

// $user MUST BE an instance of model User
$user = User::find(1); 
$canMakeRequest = $user->canMakeRequestToGemini();
Is active
This function is for check if the user is active. The function returs a boolean value

// $user MUST BE an instance of model User
$user = User::find(1); 
$canMakeRequest = $user->isActiveInGemini();
The difference between isActive and canMakeRequest is that canMakeRequest works based on the limit of tokens and daily and monthly requests and isActive is a more manual and controlled way of controlling the use.

Update usage
This method updates gemini usage, increments the request counter, and increments the token counter. As a parameter it receives the number of tokens used by a prompt as an integer.

// $user MUST BE an instance of model User
$user = User::find(1); 
$user->updateUsageTracking($tokens);
Store Gemini Request
This function is intended to store data from the request to gemini, this function does not affect the tracking of user usage, it only stores data.

$testUser->storeGeminiRequest(requestType: "Test",
 consumedTokens: $tokens, 
 requestSuccessful: true, 
 requestData: ["request"=> $prompt],
 responseData: ["response"=> $response]); 
Examples
Text-Based Chat
You can keep chat history easily:

$gemini = Gemini::newChat();
$response = $gemini->newPrompt('How much is 1 + 1?');
$followUp = $gemini->newPrompt('Add 8 to the previous result');
Image-Based Chat
You can combine image prompt and text prompt in the only one prompt

$testImagePath = storage_path('app/public/test_image.jpeg');
$uploadedFile = UploadFileToGemini::processFileFromPath($testImagePath);

$gemini = Gemini::newChat();
$response = $gemini->newPrompt(
    "What do you see in this image?",
    $uploadedFile->getUri(),
    $uploadedFile->getMimeType()
);
Changing Configuration at Runtime
$gemini = Gemini::newChat();
$gemini->setGeminiModelConfig(1, 40, 0.95, 8192, 'text/plain');
$response = $gemini->newPrompt('Generate a creative story');
JSON Mode Chat
Here's an example of using JSON mode to generate cookie recipes:

$responseSchema = [
    "responseSchema" => [
        "type" => "object",
        "description" => "Return some of the most popular cookie recipes",
        "properties" => [
            "recipes" => [
                "type" => "array",
                "items" => [
                    "type" => "object",
                    "properties" => [
                        "recipe_name" => [
                            "type" => "string",
                            "description" => "name of recipe using upper case"
                        ],
                        "ingredients_number" => [
                            "type" => "number"
                        ]
                    ],
                    "required" => [
                        "recipe_name",
                        "ingredients_number"
                    ]
                ]
            ],
            "status_response" => [
                "type" => "array",
                "items" => [
                    "type" => "object",
                    "properties" => [
                        "sucess" => [
                            "type" => "string",
                            "description" => "Short message in uppercase about request"
                        ],
                        "code" => [
                            "type" => "string",
                            "description" => "Status code",
                            "enum" => [
                                "200",
                                "400"
                            ]
                        ]
                    ],
                    "required" => [
                        "sucess",
                        "code"
                    ]
                ]
            ]
        ],
        "required" => [
            "recipes",
            "status_response"
        ]
    ]
];

$geminiChat = Gemini::newChat();
$geminiChat->setGeminiModelConfig(
    temperature: 1,
    topK: 40,
    topP: 0.95,
    maxOutputTokens: 8192,
    responseMimeType: 'application/json',
    responseSchema: $responseSchema
);

$response = $geminiChat->newPrompt("Generate a list of cookie recipes. Make the outputs in JSON format.");

$responseObject = json_decode($response);
$recipes = $responseObject->recipes;
$firstRecipeName = $recipes[0]->recipe_name;

// You can now work with the structured JSON response
Changing Gemini Model Example
You can compare responses from different Gemini models:

$geminiChat1 = Gemini::newChat();
$geminiChat1->changeGeminiModel("gemini-1.5-flash-8b");
$response1 = $geminiChat1->newPrompt("Your prompt here");

$geminiChat2 = Gemini::newChat();
$geminiChat2->changeGeminiModel("gemini-1.5-pro-002");
$response2 = $geminiChat2->newPrompt("Your prompt here");

// Compare $response1 and $response2
Getting Current Model Configuration Example
You can compare responses from different Gemini models:

use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;

$geminiChat = Gemini::newChat();

// Get initial configuration
$initialModelConfig = $geminiChat->getGeminiModelConfig();

// Change configuration
$geminiChat->setGeminiModelConfig(2, 64, 1, 8192, 'text/plain');

// Get updated configuration
$updatedModelConfig = $geminiChat->getGeminiModelConfig();

// Now you can compare or use these configurations
Count the number of tokens in a given text.
$tokens = GeminiTokenCount::coutTextTokens("Hello Gemini can you write a funny story");
Check if the user can make a request to Gemini.
$user = User::find(1);
$canMakeRequest = $user->canMakeRequestToGemini();

return response()->json([
    'success'=> true,
    'message'=> 'Everything okay',
    'User can make request ' => $canMakeRequest
],200);
Check if the user is active in Gemini.
$user = User::find(1);
$isActive = $user->isActiveInGemini();

return response()->json([
    'success'=> true,
    'message'=> 'Everything okay',
    'User is active' => $isActive
],200);
Update the usage tracking for the user.
$tokens = GeminiTokenCount::coutTextTokens("Write a teror story for my class");
$user = User::find(1);

if(!$user->canMakeRequestToGemini()){
    return response()->json([
        "success"=> false,
        "message"=> "User unauthorized",
    ],403);
}

$user->updateUsageTracking($tokens);
Assign roles to test users.
$faker = Faker::create();
$testUser = User::create([
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'password'=> Hash::make("1234"),
]);

$role = $testUser->assignGeminiRole(1);

$testUser2 = User::create([
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'password'=> Hash::make("1234"),
]);

$role = $testUser2->assignGeminiRole('limited_user');
Log the requests made by the user.
$prompt = "Write my tesis please";
$gemini = Gemini::newChat();

if(!$testUser->canMakeRequestToGemini()){
    return response()->json([
        'code' => 403,
        "success"=> true,
        "message"=> "The user cannot make requests to gemini",
    ],403);
}

$response = $gemini->newPrompt($prompt);
$tokens = GeminiTokenCount::coutTextTokens($prompt);
$testUser->updateUsageTracking($tokens);
$testUser->storeGeminiRequest(requestType: "Test", consumedTokens: $tokens, requestSuccessful: true, requestData: ["request"=> $prompt],responseData: ["response"=> $response]); 
License
MIT License

Copyright (c) 2025 José López Lara

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “liteopensource/gemini-lite-laravel”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

About
No description, website, or topics provided.
Resources
 Readme
 Activity
 Custom properties
Stars
 12 stars
Watchers
 0 watching
Forks
 0 forks
Report repository
Releases
 2 tags
Create a new release
Packages
No packages published
Publish your first package
Languages
PHP
100.0%
Suggested workflows
Based on your tech stack
SLSA Generic generator logo
SLSA Generic generator
Generate SLSA3 provenance for your existing release workflows
PHP logo
PHP
Build and test a PHP application using Composer
Symfony logo
Symfony
Test a Symfony project.
More workflows
Footer
© 2025 GitHub, Inc.
Footer navigation
Terms
Privacy
Security
Status
Docs
Contact
Manage cookies
Do not share my personal information
Copied!