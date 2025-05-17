<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Repository;
use Composer\Semver\VersionParser;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Throwable;

class IndexReleases extends Component
{
    public Repository $repository;

    public string $search;

    public int $count;

    public function mount(): void
    {
        $this->search = request()->get('search', '');
    }

    public function render(): View
    {
        $query = $this->repository->releases()
            ->when($this->search, function (Builder $query): void {
                $query->where(function (Builder $query): void {
                    $query->where('tag', 'like', '%'.$this->search.'%')
                        ->orWhere('body', 'like', '%'.$this->search.'%');
                });
            })
            ->when(request()->get('from'), function (Builder $query, $from): void {
                try {
                    $normalized = (new VersionParser)->normalize($from);
                    $query->where('version', '>=', $normalized);
                } catch (Throwable) {
                }
            })
            ->when(request()->get('to'), function (Builder $query, $to): void {
                try {
                    $normalized = (new VersionParser)->normalize($to);
                    $query->where('version', '<=', $normalized);
                } catch (Throwable) {
                }
            })
            ->orderBy('published_at', 'desc');
        $this->count = $query->count();

        return view('livewire.index-releases', [
            'releases' => $query->simplePaginate(10),
        ]);
    }
}
