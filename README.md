# Localize

> Localize allows you to easily translate i18n files within Statamic

## Features

This addon does:

- Translate i18n files within your Statamic page
- Provie an api route

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require teamnovu/localize
```

If you're using the Git integration you and want to track the used lang files,
add to `config/statamic/git.php` the following path:

```php
'paths' => [
    // ...
    base_path('lang'),
],
```
