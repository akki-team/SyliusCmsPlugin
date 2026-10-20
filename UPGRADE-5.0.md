# UPGRADE FROM `v4.4` TO `v5.0`

Version 5.0 runs on Sylius 2.3, Symfony 7.4 and PHP 8.3 to 8.5. The data model, the database tables, the namespace
(`BitBag\SyliusCmsPlugin`), the bundle name, the service identifiers, the routes and the translation keys are unchanged:
no data migration is needed and templates overridden under `templates/bundles/BitBagSyliusCmsPlugin` keep their paths.

## Requirements

- `sylius/sylius` `^2.3`, `php` `^8.3`
- `friendsofsymfony/ckeditor-bundle` `^2.7`, `symfony/webpack-encore-bundle` `^2.0`
- API Platform 4

## Configuration

- Services are declared in PHP (`Resources/config/services.php`); `services.xml` is gone. `config.yml` already imports
  the new file.
- Media files are stored with Flysystem instead of Gaufrette: the `bitbag_sylius_cms_plugin.storage` storage writes into
  `%sylius_core.public_dir%`, so existing paths (`/media/image/...`, `/media/file/...`, `/media/video/...`) keep working.
  The `bitbag_sylius_cms_plugin.uploader.filesystem` parameter and the `knp_gaufrette` configuration are removed;
  `MediaUploader` now takes a `Sylius\Component\Core\Filesystem\Adapter\FilesystemAdapterInterface`.
- The public directory is added to the `sylius_image` LiipImagine loader so that media paths resolve in Sylius 2.
- The JMS serializer metadata of the CMS models is registered only when `JMSSerializerBundle` is enabled.
- Resource interfaces are auto-discovered: the `interface` keys of the resource configuration are removed.
- `Sylius\Component\Resource\*` classes are replaced by their `Sylius\Resource\*` equivalents.

## Admin

- CRUD pages use the Sylius 2 admin (`@SyliusAdmin/shared/crud` and Twig Hooks). Each resource route sets the
  `hook_prefix` variable to `bitbag_sylius_cms_plugin.admin.<resource>`; the form templates set by `vars.templates.form`
  are rendered in the `bitbag_sylius_cms_plugin.admin.<resource>.(create|update).content.form.sections` hook.
  `CrudUi/index|create|update.html.twig` extend the Sylius 2 CRUD templates.
- Forms and widgets use Bootstrap 5. Products and taxons use the Sylius admin UX autocompletes;
  `SectionAutocompleteChoiceType` and `MediaAutocompleteChoiceType` are Symfony UX entity autocompletes (they now take
  the model class as constructor argument and are registered as services). The Semantic UI media autocomplete script is
  removed.
- `sonata_block_render_event` events of the CRUD templates are replaced by Twig Hooks.
- The section AJAX endpoints (`bitbag_sylius_cms_plugin_admin_ajax_section_by_name_phrase` and
  `bitbag_sylius_cms_plugin_admin_ajax_section_by_code`) are removed: section fields use the Symfony UX autocomplete.
- The media AJAX endpoints no longer depend on FOSRestBundle. The CKEditor media dialogs no longer use jQuery nor the
  global `route` variable: the plugin sets `window.bitbagCmsMediaSearchUrl` in the `sylius_admin.base#javascripts` hook.
- The channel switch of the preview modal uses the new `bitbag_sylius_cms_plugin_admin_partial_channel_index` route.
- The menu uses Tabler icons.

## Assets

`createCmsConfigs()` of `webpack.config.js` keeps its signature. When the separate `cms_admin` and `cms_shop` builds are
used, import the hooks that include them:

```yaml
imports:
    - { resource: "@BitBagSyliusCmsPlugin/Resources/config/twig_hooks/assets.yml" }
```

## Shop

Shop templates extend `@SyliusShop/shared/layout/base.html.twig` and use Bootstrap 5.

## API

Resources use the API Platform 3+ metadata format. Paths are unchanged (`/api/v2/shop/cms-plugin/...`); item operation
names are unchanged and collection operation names get the `_collection` suffix.
