# User Roles and Permissions

Spatie's [Laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction) package is installed to provide support for user roles and permissions.

Permissions should be assigned to users via roles, never directly.

New permissions can be added through the Permissions seeder. These should follow the `model/feature.permission` naming scheme (e.g. `user.create`). You must provide a user readable label that describes the permission via language translations in the `lang/<locale>/permissions.php` file(s).

New roles, and permission associations, can be added through the Roles seeder. The role name must be human-readable (e.g. `Manager`, `User`).

The Administrator role has no permissions assigned directly, they are permitted to anything via the `Gate::before` hook in the auth service provider.
