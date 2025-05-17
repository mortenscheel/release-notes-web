<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class IndexRepositoriesController extends Controller
{
    public function __invoke(): View
    {
        return view('repositories.index');
    }
}
