<?php

namespace Nalzai35\FilamentSettings;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Nalzai35\FilamentSettings\Commands\FilamentSettingsCommand;

require __DIR__ . '/Helpers/helpers.php';
class FilamentSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-settings')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(FilamentSettingsCommand::class);

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('setting', function () {
            return new Settings();
        });
    }

    protected function getMigrations(): array
    {
        return [
            'create_filament_settings_table',
        ];
    }
}
