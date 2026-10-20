## Installation


##### 1. Require the plugin (Sylius 2.3, PHP 8.3 to 8.5)

Version 5 is published by the akki-team fork. Declare the repository, then require the tag:

```json
"repositories": [
    { "type": "vcs", "url": "https://github.com/akki-team/SyliusCmsPlugin" }
]
```

```bash
$ composer require bitbag/cms-plugin:^5.0 --no-scripts
```

##### 2. Add plugin dependencies to your `config/bundles.php` file:

```php
return [
    ...
    BitBag\SyliusCmsPlugin\BitBagSyliusCmsPlugin::class  => ['all' => true],
];
```

##### 3. Configure a WYSIWYG editor.

The plugin supports two WYSIWYG editors: [FOS CKEditor](https://symfony.com/doc/master/bundles/FOSCKEditorBundle/usage/ckeditor.html) by default and [Trix](https://trix-editor.org/) as an alternative. 

You can choose which one you want to use by following one of the guides below:

- [Trix WYSIWYG config](./trix-config.md)*
- [CKeditor WYSIWYG config](./ckeditor-config.md)*

##### 4. Import required config in your `config/packages/_sylius.yaml` file:
```yaml
# config/packages/_sylius.yaml

imports:
    ...
    
    - { resource: "@BitBagSyliusCmsPlugin/Resources/config/config.yml" }
```

##### 5. Import routing in your `config/routes.yaml` file:

```yaml

# config/routes.yaml
...

bitbag_sylius_cms_plugin:
    resource: "@BitBagSyliusCmsPlugin/Resources/config/routing.yml"
```
If You have installed https://github.com/stefandoorn/sitemap-plugin according to its installation instructions
import optional sitemap providers:
```yaml
# config/services.yaml
...
imports:
...
    - { resource: "@BitBagSyliusCmsPlugin/Resources/config/services/sitemap_provider.yml" }
```

and plugin dependency to your `config/bundles.php` file:
```php
return [
    ...

    SitemapPlugin\SitemapPlugin::class => ['all' => true], // Sitemap support
];
```

you will probably need to change the extension of the imported file in 

```yaml
# config/packages/sitemap_plugin.yaml

    imports:
        - { resource: "@SitemapPlugin/Resources/config/config.yaml" }
```

##### 6. Finish the installation by updating the database schema and installing assets:

```bash
$ bin/console cache:clear

# If you used migrations in your project...
$ bin/console doctrine:migrations:migrate
# ... or if you use doctrine schema tool.
$ bin/console doctrine:schema:update --dump-sql # and --force switch when you're ready :)

$ bin/console assets:install --symlink
$ bin/console sylius:theme:assets:install --symlink

# CKEditor 4 (default WYSIWYG editor)
$ bin/console ckeditor:install --tag=4.22.1
$ bin/console assets:install --symlink
```

**Note.** In some cases, the `--symlink` option [may throw some errors](https://github.com/Sylius/SyliusThemeBundle/issues/91). If you consider running the commands without the `--symlink` option, please keep in mind to run them on every potential plugin update.

##### 7. Add plugin assets to your project

We recommend you to use Webpack (Encore), for which we have prepared four different instructions on how to add this plugin's assets to your project:

- [Import webpack config](./01.1-webpack-config.md)*
- [Add entry to existing config](./01.2-webpack-entry.md))
- [Import entries in your entry.js files](./01.3-import-entry.md))
- [Your own custom config](./01.4-custom-solution.md))

<small>* Default option for plugin development</small>


However, if you are not using Webpack, here are instructions on how to add optimized and compressed assets directly to your project templates:

- [Non webpack solution](./01.5-non-webpack.md)

##### 8. Load the plugin assets in the admin and the shop

When the plugin assets are built as separate `cms_admin` and `cms_shop` Encore builds (see step 7), declare both builds
in `config/packages/webpack_encore.yaml` and `config/packages/assets.yaml`, then import the hooks that include them:

```yaml
# config/packages/_sylius.yaml

imports:
    ...

    - { resource: "@BitBagSyliusCmsPlugin/Resources/config/twig_hooks/assets.yml" }
```

## Testing & running the plugin

The plugin runs on [`sylius/test-application`](https://github.com/Sylius/TestApplication), configured in `tests/TestApplication`.

```bash
$ composer install
$ (cd vendor/sylius/test-application && yarn install && yarn build)
$ APP_ENV=test vendor/bin/console ckeditor:install --clear=drop
$ APP_ENV=test vendor/bin/console assets:install
$ APP_ENV=test vendor/bin/console doctrine:database:create
$ APP_ENV=test vendor/bin/console doctrine:schema:create
$ APP_ENV=test symfony server:start --no-tls --dir=vendor/sylius/test-application/public --port=8080 -d
$ open http://localhost:8080
$ vendor/bin/phpspec run
$ vendor/bin/phpunit
$ vendor/bin/behat
```
