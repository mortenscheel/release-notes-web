<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RepositoryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use function sprintf;

/**
 * @property string $organization
 * @property string $repository
 * @property string $url
 */
class Repository extends Model
{
    /** @use HasFactory<RepositoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'stars',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
        'stars' => 'integer',
    ];

    /**
     * @return HasMany<Release, $this>
     */
    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }

    /**
     * @return HasOne<Release, $this>
     */
    public function latestRelease(): HasOne
    {
        return $this->hasOne(Release::class)->ofMany('published_at');
    }

    /** @return Attribute<string,never> */
    public function organization(): Attribute
    {

        return Attribute::get(fn (): string => str($this->name)->before('/')->value());
    }

    /** @return Attribute<string,never> */
    public function repository(): Attribute
    {
        return Attribute::get(fn (): string => str($this->name)->after('/')->value());
    }

    /** @return Attribute<non-falsy-string,never> */
    public function url(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('https://github.com/%s', $this->name));
    }

    /** @return Attribute<bool,never> */
    public function isSyncable(): Attribute
    {
        return Attribute::get(fn (): bool => $this->synced_at?->isBefore(now()->subHours(24)) ?? true);
    }

    /**
     * @param  Builder<$this>  $query
     */
    public function scopeOrderByLatestRelease(Builder $query, string $direction = 'desc'): void
    {
        $query->orderBy(Release::select('published_at')
            ->whereColumn('releases.repository_id', 'repositories.id')
            ->latest()
            ->take(1),
            $direction
        );
    }
}
