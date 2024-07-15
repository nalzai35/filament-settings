# Filament Settings

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nalzai35/filament-settings.svg?style=flat-square)](https://packagist.org/packages/nalzai35/filament-settings)
[![Total Downloads](https://img.shields.io/packagist/dt/nalzai35/filament-settings.svg?style=flat-square)](https://packagist.org/packages/nalzai35/filament-settings)

This package adds a settings page in the filaments stored in your database.

## Installation

You can install the package via composer:

```bash
composer require nalzai35/filament-settings
```
Add the plugin to your desired Filament panel:
```php
use Nalzai35\FilamentSettings\FilamentSettingsPlugin;
 
class FilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugins([
                FilamentSettingsPlugin::make()
            ]);
    }
}
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-settings-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-settings-config"
```

This is the contents of the published config file:

```php
return [
    'database_table_name' => 'settings',
    'cache_key' => 'settings'
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-settings-views"
```

## Usage

Create a settings page at `app/Filament/Pages/Settings/Settings.php`:
```php
namespace App\Filament\Pages\Settings;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Nalzai35\FilamentSettings\Pages\SettingsPage;

class Settings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public function form(Form $form): Form
    {
        return $form
                ->schema([
                    Forms\Components\Tabs::make('settings')
                        ->schema([
                            Forms\Components\Tabs\Tab::make('General')
                                ->statePath('general')
                                ->schema([
                                    Forms\Components\TextInput::make('brand_name')
                                        ->required()
                                ]),
                            Forms\Components\Tabs\Tab::make('Seo Meta')
                                ->statePath('seo_meta')
                                ->schema([
                                    Forms\Components\Section::make('Home Page')
                                        ->statePath('home_page')
                                        ->collapsible()
                                        ->schema([
                                            Forms\Components\TextInput::make('title'),
                                            Forms\Components\Textarea::make('description')
                                        ])
                                ])
                        ]),
                ])
                ->columns(1);
    }
}
```
### Retrieving settings
You can retrieve settings using the helper function `setting()`, like the `config()` function in Laravel:
```php
setting('general.name');

// Retrieve a default value if the configuration value does not exist...
setting('general.name', 'Filament Settings');

// To set configuration values at runtime
setting(['general.timezone' => 'America/Chicago']);
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [nalzai35](https://github.com/nalzai35)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
