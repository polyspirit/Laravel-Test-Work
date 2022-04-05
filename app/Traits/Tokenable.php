<?php
namespace App\Traits;

use Illuminate\Support\Str;

Trait Tokenable
{
    public function generateAndSaveApiAuthToken()
    {
        $token = Str::random(32);

        $this->api_token = $token;
        $this->save();

        return $this;
    }
}
