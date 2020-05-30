# This is a package to setup laravel for what I needed.

## Installation
`composer require leonard133/laranow`

`php artisan migrate`

## Auto-generate resource controller, model and so on.
`php artisan add:blueprint model1 model2 model3`

`-F | --force - To force overwrite the YAML file`

`-A | --api  - To create api resources, controller in API folder`

---

## Template View
Coming soon.

---

## Authentication
Able to automatically create multiple guard and defined route in a different file. (Coming soon)

---

## Packages

`php artisan add:packages`

`-X | --exclude - To exlude the default packages`

`-I | --include - To include additional packages`

Default
- Laravel UI (ui) (unable to exclude)
- Laravel Debugbar (debugbar)
- Laravel Telescope (telescope)
- Laravel Spatie Permission (permission)
- Laravel Datatable Core (datatable)
- Laravel Datatable Html (datatable-html)
- Laravel Datatable Buttons (datatable-button)
Additional
- Laravel Horizon (horizon)
- Laravel Backup (backup)
- Laravel Slack Notification (slack)
- Laravel Excel (excel)
- Laravel Passport (passport)

---

## Credits
- [laravel-shift/blueprint](https://github.com/laravel-shift/blueprint)
- [laravel/ui](https://github.com/laravel/ui)
- [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)
- [laravel/telescope](https://github.com/laravel/telescope)
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
- [yajra/laravel-datatables](https://github.com/yajra/laravel-datatables)
