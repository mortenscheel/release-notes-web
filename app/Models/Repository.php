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

class Repository extends Model
{
    /** @use HasFactory<RepositoryFactory> */
    use HasFactory;

    protected $fillable = [
        'organization',
        'repository',
        'description',
        'stars',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'timestamp',
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

    /**
     * @param  Builder<$this>  $query
     */
    public function scopeSyncable(Builder $query): void
    {
        $query->where(function (Builder $query): void {
            $query->whereNull('synced_at')
                ->orWhere('synced_at', '<', now()->subHours(24));
        });
    }

    /** @return Attribute<non-falsy-string,never> */
    public function fullName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('%s/%s', $this->organization, $this->repository));
    }

    /** @return Attribute<non-falsy-string,never> */
    public function url(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('https://github.com/%s/%s', $this->organization,
            $this->repository));
    }
}
