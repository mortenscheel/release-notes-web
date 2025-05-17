<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Contracts\View\View;

class ShowRepositoryController extends Controller
{
    public function __invoke(string $organization, string $repository): View
    {
        $repo = Repository::whereOrganization($organization)->whereRepository($repository)->firstOrFail();

        return view('repositories.show', ['repo' => $repo]);
    }
}
