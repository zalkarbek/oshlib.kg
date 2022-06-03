<?php

namespace App\Http\Controllers\API;

class TestAPIController
{
    public function index(Request $request): string
    {
        return 'Hello';
    }
}
