# RUNTRACKER

![CI](https://github.com/arzamaskov/runtracker/actions/workflows/ci.yml/badge.svg)
![CD](https://github.com/arzamaskov/runtracker/actions/workflows/cd.yml/badge.svg)
![PHP](https://img.shields.io/badge/php-8.4-777bb4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/symfony-7.4-000000?logo=symfony&logoColor=white)

**RunTracker** is a modern web application for deep analysis and tracking of running workouts. The project is built using advanced engineering practices such as Domain-Driven Design (DDD) and Hexagonal Architecture, ensuring high reliability and scalability.

## Technologies

- **Backend**: Symfony 7.4 (PHP 8.4), PostgreSQL 16, Redis 7
- **Frontend**: SvelteKit, TypeScript, Tailwind CSS
- **Infrastructure**: Docker, GitHub Actions, Nginx
- **Dev tools**: Mailpit (email testing), Xdebug

## Architecture & Standards

The project follows **Hexagonal Architecture** and **DDD** principles.

- **Layers**: Domain, Application, Infrastructure, Presentation.
- **Modularity**: Separated into Bounded Contexts (Identity, Shared, Training, etc.).
- **Code Quality**:
    - **PHP CS Fixer** (PSR-12 coding style)
    - **PHPStan** (static analysis)
    - **Deptrac** (architectural layer control)
    - **ESLint/Prettier** (Frontend)

## Quick Start

### Prerequisites
- Docker & Docker Compose
- Make

### Build & Run

```bash
make build
make up
```

### Access

| Service    | URL                          |
|------------|------------------------------|
| Frontend   | http://localhost              |
| Vite HMR   | http://localhost:5173         |
| Mailpit    | http://localhost:8025         |
| PostgreSQL | localhost:5432 (user: `app`)  |
| Redis      | localhost:6379               |

### Key Commands

```bash
# Quality checks (linters, static analysis, tests)
make ci

# Individual checks
make lint          # PHP CS Fixer (dry-run)
make lint-fix      # PHP CS Fixer (auto-fix)
make lint-js       # ESLint
make stan          # PHPStan
make deptrac       # Deptrac
make test          # PHPUnit
make test-coverage # PHPUnit with coverage report

# Composer / pnpm
make composer cmd="require vendor/package"
make pnpm CMD="add -D some-package"

# Symfony console
make console cmd="about"
make cc             # Clear cache

# Database
make migrate        # Run migrations
make migrate-diff   # Generate migration
make psql           # PostgreSQL console

# Shells
make shell          # PHP-FPM container
make shell-frontend # Frontend container

# Info
make help           # All available commands
make info           # Project info & versions
```

## Development Workflow

### Git Workflow & Commits

We use **Conventional Commits**: `<type>(scope): <description>`.
Examples: `feat(auth): login`, `fix(api): validation error`.

1. **Branches**: `main` (stable), `feature/*` (development).
2. **PR**: Create PR to `main`, CI runs automatically.
3. **Release**: Creating a tag (e.g., `v1.0.0`) triggers the CD pipeline: builds images and deploys to production.

```bash
# Create release
git tag v1.0.0
git push origin v1.0.0
```
