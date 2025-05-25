<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Release;
use App\Models\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithPagination;
use Log;

use function redirect;
use function version_compare;

class IndexReleases extends Component
{
    use WithPagination;

    public Repository $repository;

    #[Url]
    public string $search = '';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    /** @var Collection<int, string> */
    public Collection $tags;

    public function mount(string $name): Redirector|RedirectResponse|null
    {
        if (! $repository = Repository::whereName($name)->first()) {
            return redirect()->route('repositories.index')->error("Repository $name not found.");
        }
        $this->repository = $repository;
        $this->tags = $this->repository->releases()
            ->pluck('tag')
            ->sortDesc(SORT_NATURAL);

        return null;
    }

    /** @return Builder<Release> */
    #[Computed]
    public function query(): mixed
    {
        $query = $this->repository->releases()
            ->when($this->search, function (Builder $query): void {
                $query->where(function (Builder $query): void {
                    $query->whereRaw('LOWER(body) LIKE ?', ['%'.strtolower($this->search).'%'])
                        ->orWhere('tag', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->from, function (Builder $query, $from): void {
                $query->whereVersion('>=', $from);
            })
            ->when($this->to, function (Builder $query, $to): void {
                $query->whereVersion('<=', $to);

            })
            ->when($this->from || $this->to,
                fn (Builder $query) => $query->orderByVersion(),
                fn (Builder $query) => $query->orderBy('published_at', 'desc')
            );
        Log::debug($query->toRawSql(), ['count' => $query->count()]);

        return $query;
    }

    /** @return string[] */
    #[Computed]
    public function fromTags(): array
    {
        return $this->tags->when($this->to, fn (Collection $tags) => $tags->filter(fn (string $tag): bool => version_compare($tag, $this->to, '<=')))->toArray();
    }

    /** @return string[] */
    #[Computed]
    public function toTags(): array
    {
        return $this->tags->when($this->from, fn (Collection $tags) => $tags->filter(fn (string $tag): bool => version_compare($tag, $this->from, '>=')))->toArray();
    }

    public function resetSearch(): void
    {
        $this->reset(['search', 'from', 'to']);
    }

    #[Title('Releases')]
    public function render(): View
    {
        return view('livewire.index-releases');
    }
}
