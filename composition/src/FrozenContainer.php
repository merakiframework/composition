<?php
declare(strict_types=1);

namespace Meraki\Composition;

use Meraki\Composition\ContainerDefinition;
use Meraki\Wiring\ContainerDefinitionList;
use Meraki\Wiring\ResolutionPath;
use Psr\Container\ContainerInterface as PsrContainer;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use Closure;
use ReflectionUnionType;

final class FrozenContainer implements PsrContainer
{
	/** cache for shared instances */
	private array $shared = [];

	private ResolutionPath $resolutionPath;

	public function __construct(private ContainerDefinitionList $definitions)
	{
		$this->resolutionPath = new ResolutionPath();

		$this->definitions->add(new ContainerDefinition(PsrContainer::class, $this));
	}

	public function has(string $id): bool
	{
		return $this->definitions->has($id) || class_exists($id, true);
	}

	public function get(string $id): mixed
	{
		$isRootResolution = $this->resolutionPath->count() === 0;
		$this->resolutionPath = $this->resolutionPath->push($id);

		try {
			return $this->resolve($id);
		} finally {
			if ($isRootResolution) {
				$this->resolutionPath = $this->resolutionPath->reset();
			} else {
				$this->resolutionPath = $this->resolutionPath->pop();
			}
		}
	}

	private function resolve(string $id): mixed
	{
		if (isset($this->shared[$id])) {
			return $this->shared[$id];
		}

		if ($this->definitions->has($id)) {
			return $this->resolveDefinition($this->definitions->get($id));
		}

		if (class_exists($id, true)) {
			return $this->resolveDefinition(new ContainerDefinition($id));
		}

		throw new Exception\CouldNotResolve($id);
	}

	private function resolveDefinition(ContainerDefinition $definition): mixed
	{
		$def = $definition->definition;
		$instance = match (true) {
			$definition->isConcrete() && !is_callable($def) => $def,
			$definition->isAliased() => $this->instantiateClass($def),
			$definition->isFactory() => $this->callFactoryAndReturn($definition),
			$definition->notProvided() => $this->instantiateClass($definition->id),
			default => null,
		};

		if ($instance !== null) {
			if ($definition->shared) {
				$this->shared[$definition->id] = $instance;
			}

			return $instance;
		}

		throw new Exception\CouldNotInstantiate($definition->id);
	}

	/**
	 * Instantiate a class, autowiring constructor arguments.
	 */
	private function instantiateClass(string $className): object
	{
		$ref = new ReflectionClass($className);

		if (!$ref->isInstantiable()) {
			throw new Exception\CouldNotInstantiate($className);
		}

		$ctor = $ref->getConstructor();

		if ($ctor === null || $ctor->getNumberOfParameters() === 0) {
			return $ref->newInstance();
		}

		$args = $this->resolveParameters($ctor->getParameters());

		return $ref->newInstanceArgs($args);
	}

	/**
	 * Calls a factory (callable). If it's a closure, we resolve its parameters and call with them.
	 * If factory expects PSR container, we pass $this. Parameters that are class-typed get resolved from container.
	 */
	private function callFactoryAndReturn(ContainerDefinition $definition): mixed
	{
		$ref = new ReflectionFunction(Closure::fromCallable($definition->definition));
		$params = $ref->getParameters();
		$args = $this->resolveParameters($params);

		return $ref->invokeArgs($args);
	}

	/**
	 * Resolve an array of ReflectionParameter into actual argument values.
	 * - For class-typed parameters, resolve via container.
	 * - For parameters with default values, use default.
	 * - For untyped parameters without default, throw.
	 */
	private function resolveParameters(array $params): array
	{
		return array_map(
			fn (ReflectionParameter $param): mixed => $this->resolveParameter($param),
			$params
		);
	}

	private function resolveParameter(ReflectionParameter $parameter): mixed
	{
		$type = $parameter->getType();

		// If parameter has default value, use it
		if ($parameter->isDefaultValueAvailable()) {
			return $parameter->getDefaultValue();
		}

		// No typehint: use default or fail
		if (!$type) {
			// if ($parameter->isDefaultValueAvailable()) {
			// 	return $parameter->getDefaultValue();
			// }

			throw new RuntimeException("Cannot resolve untyped parameter \${$parameter->getName()}");
			// throw new Exception\CouldNotResolveUntypedParameter($parameter);
		}

		// Handle named (single) types
		if ($type instanceof ReflectionNamedType) {
			$typeName = $type->getName();

			// Built-in types: use default or fail
			if ($type->isBuiltin()) {
				// if ($parameter->isDefaultValueAvailable()) {
				// 	return $parameter->getDefaultValue();
				// }

				throw new RuntimeException("Cannot resolve builtin parameter \${$parameter->getName()} ({$typeName})");
			}

			// Nullable types: leave alone unless explicitly defined
			if ($type->allowsNull()) {
				// Only inject if explicitly defined in container
				// if ($this->has($typeName)) {
				// 	return $this->get($typeName);
				// }

				// Otherwise return default or null
				return $parameter->isDefaultValueAvailable()
					? $parameter->getDefaultValue()
					: null;
			}

			// Non-nullable object types: always resolve
			return $this->get($typeName);
		}

		// Handle union types (PHP 8.0+)
		if ($type instanceof ReflectionUnionType) {
			// Try to resolve any class type that’s registered
			foreach ($type->getTypes() as $unionType) {
				if ($unionType instanceof ReflectionNamedType && !$unionType->isBuiltin()) {
					$typeName = $unionType->getName();

					// if ($this->has($typeName)) {
					// 	return $this->get($typeName);
					// }
				}
			}

			// If union includes null or a default, return null/default
			if ($type->allowsNull()) {
				return $parameter->isDefaultValueAvailable()
					? $parameter->getDefaultValue()
					: null;
			}
		}

		throw new RuntimeException("Unable to resolve parameter \${$parameter->getName()}");
	}
}
