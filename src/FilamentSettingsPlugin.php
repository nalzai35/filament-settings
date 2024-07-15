<?php

namespace Nalzai35\FilamentSettings;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentSettingsPlugin implements Plugin
{
    public array $pages = [];

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-settings';
    }

    public function register(Panel $panel): void
    {
        $panel->pages($this->getPages());
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }

    private function getPages(): array
    {
        return $this->pages;
    }
}
