<?php

declare(strict_types=1);

use SoloTerm\Solo\Commands\Command;
use SoloTerm\Solo\Commands\EnhancedTailCommand;
use SoloTerm\Solo\Commands\MakeCommand;
use SoloTerm\Solo\Hotkeys\DefaultHotkeys;
use SoloTerm\Solo\Hotkeys\VimHotkeys;
use SoloTerm\Solo\Manager;
use SoloTerm\Solo\Themes;
use SoloTerm\Solo\Themes\DarkTheme;
use SoloTerm\Solo\Themes\LightTheme;

// Solo may not (should not!) exist in prod, so we have to
// check here first to see if it's installed.
if (! class_exists(Manager::class)) {
    return [
        //
    ];
}

return [
    /*
    |--------------------------------------------------------------------------
    | Themes
    |--------------------------------------------------------------------------
    */
    'theme' => env('SOLO_THEME', 'dark'),

    'themes' => [
        'light' => LightTheme::class,
        'dark' => DarkTheme::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Keybindings
    |--------------------------------------------------------------------------
    */
    'keybinding' => env('SOLO_KEYBINDING', 'default'),

    'keybindings' => [
        'default' => DefaultHotkeys::class,
        'vim' => VimHotkeys::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Commands
    |--------------------------------------------------------------------------
    |
    */
    'commands' => [
        // 'About' => 'php artisan solo:about',
        'Logs' => EnhancedTailCommand::file(storage_path('logs/laravel.log')),
        'Vite' => 'npm run dev',
        // 'Make' => new MakeCommand,
        'HTTP' => 'php artisan serve',

        // Lazy commands do not automatically start when Solo starts.
        'PhpStan' => Command::from('vendor/bin/phpstan analyse --memory-limit=-1 --pro')->lazy(),
        'Dumps' => Command::from('php artisan solo:dumps')->lazy(),
        'Reverb' => Command::from('php artisan reverb:start --debug')->lazy(),
        // 'Pint' => Command::from('./vendor/bin/pint --ansi')->lazy(),
        // 'Queue' => Command::from('php artisan queue:work')->lazy(),
        // 'Tests' => Command::from('php artisan test --colors=always')->withEnv(['APP_ENV' => 'testing'])->lazy(),
    ],

    /**
     * By default, we prefer to use GNU Screen as an intermediary between Solo
     * and the underlying process. This helps us with many issues, including
     * PTY and some ANSI rendering things. Not all environments have Screen,
     * so you can turn it off for a slightly degraded experience.
     */
    'use_screen' => (bool) env('SOLO_USE_SCREEN', false),

    /*
    |--------------------------------------------------------------------------
    | Miscellaneous
    |--------------------------------------------------------------------------
    */

    /*
     * If you run the solo:dumps command, Solo will start a server to receive
     * the dumps. This is the address. You probably don't need to change
     * this unless the default is already taken for some reason.
     */
    'dump_server_host' => env('SOLO_DUMP_SERVER_HOST', 'tcp://127.0.0.1:9984'),
];
