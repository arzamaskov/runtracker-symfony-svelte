# RUNTRACKER

![CI](https://github.com/arzamaskov/runtracker/actions/workflows/ci.yml/badge.svg)
![CD](https://github.com/arzamaskov/runtracker/actions/workflows/cd.yml/badge.svg)
![PHP](https://img.shields.io/badge/php-8.4-777bb4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/symfony-7.4-000000?logo=symfony&logoColor=white)

**RunTracker** — это современное веб-приложение для глубокого анализа и отслеживания беговых тренировок. Проект разработан с использованием передовых практик, таких как Domain-Driven Design (DDD) и Гексагональная архитектура (Hexagonal Architecture), что обеспечивает высокую надежность и масштабируемость.

## Технологии

- **Backend**: Symfony 7.4 (PHP 8.4), PostgreSQL 16
- **Frontend**: SvelteKit, TypeScript, Tailwind CSS
- **Инфраструктура**: Docker, GitHub Actions

## Архитектура и Стандарты

Проект следует принципам **Hexagonal Architecture** и **DDD**.

- **Слои**: Domain, Application, Infrastructure, Presentation.
- **Модульность**: Разделение на Bounded Contexts (Identity, Shared, Training и др.).
- **Качество кода**:
    - **PHP CS Fixer** (стиль кода PSR-12)
    - **PHPStan** (статический анализ)
    - **Deptrac** (контроль архитектурных слоев)
    - **ESLint/Prettier** (Frontend)

## Быстрый Старт

### Требования
- Docker & Docker Compose
- Make

### Основные команды

```bash
# Сборка и запуск
make build
make up

# Backend shell
make shell

# Проверка качества (тесты, линтеры, статический анализ)
make ci
```

## Процесс Разработки

### Git Workflow и Коммиты

Используется стандарт **Conventional Commits**: `<type>(scope): <description>`.
Примеры: `feat(auth): login`, `fix(api): validation error`.

1. **Ветки**: `main` (стабильная), `feature/*` (разработка).
2. **PR**: Создается в `main`, автоматически запускается CI.
3. **Релиз**: При создании тега (например, `v1.0.0`) запускается CD пайплайн: сборка образов и деплой на прод.

```bash
# Создание релиза
git tag v1.0.0
git push origin v1.0.0
```
