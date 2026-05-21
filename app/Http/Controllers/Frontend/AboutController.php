<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\View\View;

class AboutController
{
    public function __invoke(): View
    {
        return view('frontend.pages.about');
    }
}

