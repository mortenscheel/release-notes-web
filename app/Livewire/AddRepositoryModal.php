<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Github\GithubService;
use App\Jobs\SyncRepositoryReleasesJob;
use App\Models\Repository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class AddRepositoryModal extends Component
{
    #[Validate('required|string')]
    public string $organization = '';

    #[Validate('required|string')]
    public string $repository = '';

    public function save(): void
    {
        $params = $this->validate();
        if (Repository::where($params)->exists()) {
            $this->addError('repository', 'Repository already exists.');

            return;
        }
        try {
            $githubRepo = app(GithubService::class)->getGithubRepository($params['organization'], $params['repository']);
            $repo = Repository::create($githubRepo->toArray());
            SyncRepositoryReleasesJob::dispatchSync($repo);
            $this->redirectRoute('repositories.show', $params);
        } catch (Throwable $e) {
            $this->addError('repository', $e->getMessage());
        }
    }

    public function resetForm(): void
    {
        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.add-repository-modal');
    }
}
