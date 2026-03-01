# AGENTS.md

This file provides guidance to LLM Agents when working with code in this repository.

## Project Overview

Laravel YouTrack SDK — a Laravel/Lumen service provider wrapping `cybercog/youtrack-rest-php`. The package has a single source file (`src/YouTrackServiceProvider.php`) that binds the YouTrack REST client into Laravel's service container.

## Commands

All commands run through Docker:

```bash
docker compose up -d --build                                    # Start container
docker compose exec php81 composer install                      # Install dependencies
docker compose exec php81 composer test                         # Run tests
docker compose exec php81 vendor/bin/phpunit --filter=MethodName  # Run a single test
```

No separate build or lint commands. Code style is enforced externally by StyleCI (Laravel preset).

## Architecture

The entire package is one class: `YouTrackServiceProvider`. It:
1. Binds `Cog\Contracts\YouTrack\Rest\Client\Client` to a concrete `YouTrackClient` in the container
2. Configures a `GuzzleHttpClient` with the base URI from config
3. Resolves the authorizer strategy (`token` or `cookie`) from config
4. Publishes `config/youtrack.php` (Laravel) or calls `$app->configure()` (Lumen)

All HTTP client, authorizer, and response classes come from the `cybercog/youtrack-rest-php` dependency — this package only provides the Laravel integration layer.

## Testing

- Framework: PHPUnit + Orchestra Testbench
- Base class: `tests/AbstractTestCase.php` extends `Orchestra\Testbench\TestCase`
- Test env variables are set in `phpunit.xml.dist`
- Tests use `/** @test */` annotation style

## Compatibility Matrix

- PHP: 8.1–8.5
- Laravel: 9–13 (illuminate/support)
- Testbench: 7–11
- PHPUnit: 9–11

## Code Conventions

- `declare(strict_types=1)` in every PHP file
- `final` classes
- Namespace: `Cog\Laravel\YouTrack` (src), `Cog\Tests\Laravel\YouTrack` (tests)
- License header block at the top of every PHP file
