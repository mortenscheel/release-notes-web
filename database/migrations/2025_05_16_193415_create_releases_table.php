<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('releases', function (Blueprint $table): void {
            $table->increments('id');
            $table->foreignId('repository_id')->constrained('repositories')->onDelete('cascade');
            $table->string('tag');
            $table->unsignedTinyInteger('major');
            $table->unsignedTinyInteger('minor');
            $table->unsignedTinyInteger('patch');
            $table->string('stability');
            $table->string('url');
            $table->text('body')->nullable();
            $table->timestamp('published_at');
            $table->timestamps();
            $table->unique(['repository_id', 'tag']);
            $table->index(['repository_id', 'major', 'minor', 'patch']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
