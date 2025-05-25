<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Jobs\SyncRepositoryReleasesJob;
use App\Models\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toastable;
use Masmerise\Toaster\Toaster;

use function strtolower;

class IndexRepositories extends Component
{
    use Toastable, WithPagination;

    #[Url]
    public string $search = '';

    public int $count;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function refreshAll(): void
    {
        Repository::all()->each(fn (Repository $repo) => SyncRepositoryReleasesJob::dispatchSync($repo));
        Toaster::success('↺ All repositories refreshed');
    }

    public function syncRepository(Repository $repository): void
    {
        SyncRepositoryReleasesJob::dispatchSync($repository);
        Toaster::success("$repository->name refreshed");
    }

    public function render(): View
    {
        $query = Repository::with('latestRelease')
            ->orderByLatestRelease()
            ->withCount('releases')
            ->when($this->search, function (Builder $query): void {
                $query->whereRaw('LOWER(name) LIKE ?', '%'.strtolower($this->search).'%');
            });
        $this->count = $query->count();

        return view('livewire.index-repositories', [
            'repositories' => $query->paginate(10),
        ])->title('Repositories');
    }
}
