# StarWatch Collective

[StarWatch](https://starwatch.club) is a small Laravel application for a federation of amateur astronomy
clubs. It is meant to loosely represent how InterNACHI approaches Laravel applications (as of May 2026).
Its main purpose is to serve as the target for coding exercises, but can also be used to understand
packages and conventions used at InterNACHI like [modular](https://github.com/InterNACHI/modular),
[aire](https://airephp.com/), [gretel](https://github.com/glhd/gretel), and more.

## Setup

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
yarn install && yarn dev
php artisan serve
```

Visit http://localhost:8000 and sign in:

- `admin@starwatch.test`  / `password` (admin)
- `aurora@starwatch.test` / `password` (organizer, Aurora Lodge)
- `nova@starwatch.test`   / `password` (member)
