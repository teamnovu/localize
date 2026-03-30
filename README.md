# Localize

> Localize allows you to easily translate i18n files within Statamic.

## Features

- **In-Panel Translation:** Translate i18n files directly within your Statamic control panel.
- **Laravel Localization Support:** Works with Laravel's JSON localization files.
- **Custom Files:** You can define the folder and files to be used for translations.
- **JSON API Exposure:** Optionally expose your i18n files as a JSON API for use in frontends or other services.

## Installation

You can install Localize via the Statamic control panel or using Composer:

### Statamic Control Panel

1. Go to `Tools > Addons` in your Statamic control panel.
2. Search for **Localize** and click **Install**.

### Composer

From your project root, run:

```bash
composer require teamnovu/localize
```

## Configuration


### Publishing Configuration

After installation, you may wish to publish the package configuration:

```bash
php artisan vendor:publish --provider="TeamNovu\\Localize\\LocalizeServiceProvider"
```

This will create a `localize.php` config file in your `config` directory.

### Git Integration

If you use Statamic's Git integration and want to track changes to your language files, add the following path to `config/statamic/git.php`:

```php
'paths' => [
    // ...
    base_path('lang'),
],
```

## API

If you enable the JSON API, your translations can be accessed programmatically. See the package configuration for details.
If not changed the default route is `/api/localize/LANG.json`

## Support

For issues, feature requests, or contributions, please open an issue or pull request on [GitHub](https://github.com/teamnovu/localize).
