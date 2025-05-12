# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

- Run tests: `make test`
- Run specific suite: `make test-<suite_name>` 
- Run specific test: `vendor/bin/phpunit tests/Path/To/TestFile.php`
- Build assets: `make ready`
- Install dependencies: `make vendor`
- Update database: `make update_symfony`
- Reset database: `make reset-db`

## Code Style Guidelines

- PHP 8.4 with strict typing
- Symfony 7 coding standards
- Entity attributes use PHP 8 #[Attribute] syntax
- Return types: Use explicit return types including ?Type for nullable
- Method chaining: Return $this for fluent interface pattern
- Naming: camelCase for methods/properties, PascalCase for classes
- Enum class naming: Singular (TaskStatus vs TaskStatuses)
- Entity properties: Type declarations with explicit null handling
- Error handling: Use specific exceptions with descriptive messages
- DocBlocks: Required for collection returns with generic types
- Translations: Use translation keys in messages.en.yaml/fr.yaml