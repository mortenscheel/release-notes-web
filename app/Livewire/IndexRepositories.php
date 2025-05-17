<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

use function strtolower;

class IndexRepositories extends Component
{
    use WithPagination;

    public string $search;

    public int $count;

    public function mount(): void
    {
        $this->search = request()->get('search', '');
    }

    public function render(): View
    {
        $query = Repository::with('latestRelease')
            ->withCount('releases')
            ->when($this->search, function (Builder $query): void {
                $query->where(function (Builder $query): void {
                    $query->whereRaw('LOWER(organization) LIKE ?', '%'.strtolower($this->search).'%')
                        ->orWhereRaw('LOWER(repository) LIKE ?', '%'.strtolower($this->search).'%');
                });
            });
        $this->count = $query->count();

        return view('livewire.index-repositories', [
            'repositories' => $query->paginate(10),
        ]);
    }
}
