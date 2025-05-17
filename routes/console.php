<?php

declare(strict_types=1);

use App\Console\Commands\SyncNextRepositoryCommand;

Schedule::command(SyncNextRepositoryCommand::class)->everyFiveMinutes();
