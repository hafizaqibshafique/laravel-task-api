# Laravel Task API

A small, focused REST API for managing tasks — built to show how I structure a real Laravel service, not a tutorial project. Token auth via Sanctum, form request validation, API resources, and feature tests covering the actual behavior (ownership checks, validation errors, pagination).

## Stack

- Laravel 11
- Laravel Sanctum (token auth)
- PHPUnit (feature tests)
- SQLite for local/testing

## Why it's built this way

- **Form Requests** (`StoreTaskRequest`, `UpdateTaskRequest`) keep validation out of the controller and make authorization per-action explicit.
- **API Resources** (`TaskResource`) decouple the DB schema from the JSON contract — renaming a column never breaks a client.
- **Ownership is enforced at the query level**, not just in a policy comment — a user can only ever see/edit their own tasks, verified by a test that tries to fetch another user's task and expects a 404, not a 403 (so we don't leak existence).
- **Tests hit the actual HTTP layer** (`tests/Feature/TaskApiTest.php`), not just models, so they catch routing/middleware regressions too.

## Endpoints

| Method | Endpoint            | Description                          |
|--------|---------------------|---------------------------------------|
| POST   | `/api/register`     | Create an account, returns a token   |
| POST   | `/api/login`         | Exchange credentials for a token     |
| GET    | `/api/tasks`         | List the authenticated user's tasks (paginated, filterable by `?status=`) |
| POST   | `/api/tasks`         | Create a task                        |
| GET    | `/api/tasks/{task}`  | Show a single task (owner only)      |
| PUT    | `/api/tasks/{task}`  | Update a task (owner only)           |
| DELETE | `/api/tasks/{task}`  | Delete a task (owner only)           |

All `/api/tasks/*` routes require `Authorization: Bearer <token>`.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

## Running tests

```bash
php artisan test
```

## Example request

```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
      -d '{"title": "Ship the invoice module", "status": "pending", "due_date": "2026-10-15"}'
      ```

      ---
      Built by [Aqib Shafique](https://aqib.net) — PHP/Laravel, Python, WordPress/WooCommerce, AI automation.
