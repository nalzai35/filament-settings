# Filament Settings

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nalzai35/filament-settings.svg?style=flat-square)](https://packagist.org/packages/nalzai35/filament-settings)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nalzai35/filament-settings/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nalzai35/filament-settings/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nalzai35/filament-settings/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nalzai35/filament-settings/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nalzai35/filament-settings.svg?style=flat-square)](https://packagist.org/packages/nalzai35/filament-settings)

This package adds a settings page in the filaments stored in your database.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/filament-settings.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/filament-settings)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

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
                    ->pages([
                        // Add your own setting pages here
                    ])
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

Create a settings page at 'app/Filament/Pages/Settings/Settings.php':
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

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [nalzai35](https://github.com/nalzai35)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
