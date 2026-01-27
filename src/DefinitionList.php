<?php
declare(strict_types=1);

namespace Meraki\Container;

use Meraki\Container\Definition;
use InvalidArgumentException;

final class DefinitionList
{
	private array $definitions = [];

	public function __construct(array $definitions = [])
	{
		array_map($this->add(...), $definitions);
	}

	public function add(Definition $definition): void
	{
		if ($this->has($definition->id)) {
			throw new Exception\DefinitionAlreadyExists($definition);
		}

		$this->definitions[] = $definition;
	}

	public function has(string $id): bool
	{
		foreach ($this->definitions as $definition) {
			if ($definition->hasIdOf($id)) {
				return true;
			}
		}

		return false;
	}

	public function get(string $id): ?Definition
	{
		foreach ($this->definitions as $def) {
			if ($def->hasIdOf($id)) {
				return $def;
			}
		}

		return null;
	}
}
