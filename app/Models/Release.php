<?php

declare(strict_types=1);

namespace App\Models;

use App\Markdown\SearchHighlighterExtension;
use Composer\Semver\VersionParser;
use Database\Factories\ReleaseFactory;
use ElGigi\CommonMarkEmoji\EmojiExtension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

use function is_string;

class Release extends Model
{
    /** @use HasFactory<ReleaseFactory> */
    use HasFactory;

    protected $fillable = [
        'tag',
        'url',
        'body',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /** @var string[] */
    protected $touches = [
        'repository',
    ];

    /**
     * @return BelongsTo<Repository, $this>
     */
    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    /** @param Builder<$this> $query */
    public function scopeOrderByVersion(Builder $query, string $direction = 'desc'): void
    {
        $query->orderBy('major', $direction)
            ->orderBy('minor', $direction)
            ->orderBy('patch', $direction);
    }

    /** @param Builder<$this> $query */
    public function scopeWhereVersion(
        Builder $query,
        string $operator,
        string|int $major,
        ?int $minor = null,
        ?int $patch = null
    ): void {
        if (is_string($major) && $minor === null && $patch === null) {
            $normalized = (new VersionParser)->normalize($major);
            [$major, $minor, $patch] = explode('.', $normalized);
        }
        $query->whereRaw(
            "(major * 1000000 + minor * 1000 + patch) $operator (? * 1000000 + ? * 1000 + ?)",
            [(int) $major, (int) $minor, (int) $patch]
        );
    }

    public function renderMarkdown(?string $search = null): string
    {
        if (! $this->body) {
            return '';
        }
        $extensions = [
            new EmojiExtension,
        ];
        if ($search !== null && $search !== '' && $search !== '0') {
            $extensions[] = new SearchHighlighterExtension($search);
        }

        return Str::markdown(string: $this->body, extensions: $extensions);
    }

    protected static function booted(): void
    {
        static::saving(function (Release $release): void {
            $normalized = (new VersionParser)->normalize($release->tag);
            [$major, $minor, $patch] = explode('.', $normalized);
            $release->major = (int) $major;
            $release->minor = (int) $minor;
            $release->patch = (int) $patch;
            $release->stability = VersionParser::parseStability($release->tag);
            if (trim($release->body ?? '') === '') {
                $release->body = null;
            }
        });
    }
}
