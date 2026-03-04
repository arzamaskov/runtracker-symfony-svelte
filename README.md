# RUNTRACKER

![CI](https://github.com/arzamaskov/runtracker/actions/workflows/ci.yml/badge.svg)
![CD](https://github.com/arzamaskov/runtracker/actions/workflows/cd.yml/badge.svg)
![PHP](https://img.shields.io/badge/php-8.4-777bb4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/symfony-7.4-000000?logo=symfony&logoColor=white)

**RunTracker** is a modern web application for deep analysis and tracking of running workouts. The project is built using advanced engineering practices such as Domain-Driven Design (DDD) and Hexagonal Architecture, ensuring high reliability and scalability.

## Technologies

- **Backend**: Symfony 7.4 (PHP 8.4), PostgreSQL 16
- **Frontend**: SvelteKit, TypeScript, Tailwind CSS
- **Infrastructure**: Docker, GitHub Actions

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

### Key Commands

```bash
# Build and start
make build
make up

# Backend shell
make shell

# Quality checks (tests, linters, static analysis)
make ci
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
