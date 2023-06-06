<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Requests\CountryRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CountryValidatorTest extends TestCase
{
    use RefreshDatabase;

    public function testCountryRequestValidation(): void
    {
        $data = [
            "name" => "Croatia",
            "lat" => 83.752131,
            "lon" => "-177.71159",
            "iso" => "cr",
        ];

        $validator = Validator::make($data, (new CountryRequest())->rules());

        $this->assertTrue($validator->fails());
    }
}
