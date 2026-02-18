<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class)
    ->in('Feature');

uses(Tests\TestCase::class)
    ->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectation
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getHoneypotFields(string $url, ?App\Models\User $user = null): array
{
    $request = test();
    if ($user) {
        $request = $request->actingAs($user);
    }
    $response = $request->get($url);

    $nameFieldName = config('honeypot.name_field_name');
    $validFromFieldName = config('honeypot.valid_from_field_name');

    // Updated regex to handle multi-line input tags
    // Finding name="website..."
    preg_match('/name="('.$nameFieldName.'[^"]*)"/', $response->getContent(), $nameMatches);

    // Finding name="valid_from" and its value
    // Value might be on a different line
    preg_match('/name="'.$validFromFieldName.'"\s+type="hidden"\s+value="([^"]+)"/', $response->getContent(), $validFromMatches);

    if (empty($validFromMatches)) {
        // Try another pattern if the above fails
        preg_match('/name="'.$validFromFieldName.'".*?value="([^"]+)"/s', $response->getContent(), $validFromMatches);
    }

    $fields = [
        $nameMatches[1] ?? $nameFieldName => '',
        $validFromFieldName => $validFromMatches[1] ?? '',
    ];

    return $fields;
}
