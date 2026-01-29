# Meraki\Composition

A small, explicit, and intention-driven **object composition container** for PHP.

*Meraki\Composition* focuses on building fully-constructed object graphs while keeping concerns cleanly separated between the public API and the wiring mechanics.

This library is deliberately minimal, opinionated, and designed to age well.

## Why this library exists

Most PHP DI containers blur three distinct ideas:

- **Dependency Injection (DI)** which is a technique
- **Inversion of Control (IoC)** which is a principle
- **Containers** which is infrastructure

*Meraki\Composition* makes these boundaries explicit.

- **Composition** is the contract exposed to users
- **Wiring** is the "internal machinery" that makes composition possible

This separation keeps:

- public APIs stable (including the namespace and error semantics)
- allows the internals to evolve freely or separately
- creates a DDD-like code base for easier understanding and maintenance

## Core concepts

### Composition (public)

Composition answers the question:

> "How do I construct a complete and valid object graph?"

The public surface of this library lives in the `Meraki\Composition` namespace and is intentionally kept small.

Key responsibilities:

- resolving fully-constructed objects
- exposing configuration
- enforcing resolution semantics
- defining failure behavior

Primary entry points:

- `Meraki\Composition\Container`
- `Meraki\Composition\ContainerConfig`
- `Meraki\Composition\Exception` (and subclasses)

These are the only classes most users should ever need.

### Wiring (private)

Wiring answers the question:

> "How are dependencies connected under the hood?"

The `Meraki\Wiring` namespace contains internal implementation details such as:

- resolution algorithms
- definitions and bindings
- reflection helpers
- circular dependency detection
- resolution path tracking

These classes:

- are **not** part of the public API
- may change without notice
- should not be depended on directly
- are intentionally kept separate from `Meraki\Composition` to enforce this boundary.

They exist to support composition — nothing more.

## Basic usage

```php
use Meraki\Composition\Container;

$container = new Container();

// define object graphs you don't want auto-wired
$definition = $container->define(MyInterface::class, MyImplementation::class);

// you can tweak the returned definition as needed
// For example, make it a singleton
$definition->share();

// container is now 'immutable' and can be used
// you must re-assign the variable here (A PSR container is returned)
$container = $container->freeze();

// retrieve fully constructed services
$onj = $container->get(MyInterface::class);
```

That's it!

The container will:

- recursively resolve all constructor dependencies
- auto-wire concrete classes
- respect default values
- detect circular references
- throw explicit, meaningful exceptions when resolution fails

The container config allows you to explicitly define bindings, singletons, and other behaviors as needed.

## Design principles

This library (and its development) is guided by a few strict principles:

- Constructor injection only
- No service locator usage
- No hidden dependencies
- Explicit failure over silent fallback
- Public APIs describe intent, not mechanism
- The container is infrastructure, domain code should not know it exists.
- Convention over configuration where reasonable
- Be as simple as possible, but not over clarity (in code and in-use)
- Should be usable in applications of any size, without trying to solve every problem
- Should 'age well' (easily maintainable, extensible, and understandable)
- Tests should clearly express intent and respect boundaries
- Avoid premature optimization; favor clarity and correctness first
- Adhere to SOLID principles
- Favor composition over inheritance

Finally, while this library can be used in any PHP application, it has been designed to be used in Domain-Driven Design (DDD) contexts, where clear boundaries and explicit dependencies are paramount. Therefore, it avoids patterns and practices that conflict with DDD principles. (For example, the container respect default values unless explicitly overridden, allowing entities and value objects to maintain their integrity without container interference.)

## Directory structure

The repository is structured to allow clean future extraction and independent evolution without forcing it today.

```
.
├─ composition/
│  ├─ src/
│  │  ├─ Exception/
│  │  ├─ Container.php
│  │  ├─ FrozenContainer.php
│  │  └─ etc...
│  └─ tests/
│     |─ Exception/
|     |─ Fixture/
|     └─ etc...
│
├─ wiring/
│  ├─ src/
│  │  └─ etc...
│  └─ tests/
|     ├─ Fixture/
│     └─ etc...
│
└─ integration-tests/
|     ├─ Fixture/
│     └─ etc...
```

### Why this structure?

- Composition and Wiring are siblings, not nested concepts
- Each can be extracted into its own package later with minimal effort
- Public intent and internal mechanics are visually distinct
- Test boundaries are obvious

## Testing strategy

### Composition tests

Located in:

`composition/tests/`

They test:

- container contracts
- configuration behavior
- error semantics
- parameter resolution rules

And answer:

> Does the container uphold its public contract?

### Integration tests

Located in:

`integration-tests/`

They test:

- multi-class resolution
- deep dependency graphs
- circular dependency detection
- resolution state across multiple get() calls
- the integration between Composition and Wiring

For example, `ContainerResolutionTest` answers:

> Does the container correctly resolve real object graphs?

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE.md) file for details.

Copyright (c) 2024 Nathan Bishop
