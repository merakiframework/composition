<?php
declare(strict_types=1);

namespace Meraki\Composition;

final class ContainerDefinition
{
	/** @var callable|string|object|null  callable factory OR class-string alias OR concrete object */
	public $definition;

	/** @var bool whether this should be shared (singleton) */
	public bool $shared = false;

	public function __construct(public readonly string $id, callable|string|object|null $definition = null)
	{
		$this->definition = $definition;
	}

	public function hasIdOf(string $id): bool
	{
		return $this->id === $id;
	}

	public function isConcrete(): bool
	{
		return is_object($this->definition);
	}

	public function isAliased(): bool
	{
		return is_string($this->definition);
	}

	public function isFactory(): bool
	{
		return is_callable($this->definition);
	}

	public function notProvided(): bool
	{
		return $this->definition === null;
	}

	public function share(): self
	{
		$this->shared = true;

		return $this;
	}

	public function equals(self $other): bool
	{
		return $this->id === $other->id;
	}
}
