<?php

declare(strict_types=1);

namespace App\Models;

use App\Markdown\SearchHighlighterExtension;
use Composer\Semver\VersionParser;
use Database\Factories\ReleaseFactory;
use ElGigi\CommonMarkEmoji\EmojiExtension;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

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

    /**
     * @return BelongsTo<Repository, $this>
     */
    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
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
            $release->version = (new VersionParser)->normalize($release->tag);
            $release->stability = VersionParser::parseStability($release->tag);
            if (trim($release->body ?? '') === '') {
                $release->body = null;
            }
        });
    }
}
