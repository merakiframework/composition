<?php
declare(strict_types=1);

namespace Meraki\Wiring;

use Meraki\Composition\ContainerDefinition;
use Meraki\Composition\Exception;

final class ContainerDefinitionList
{
	private array $definitions = [];

	public function __construct(array $definitions = [])
	{
		array_map($this->add(...), $definitions);
	}

	public function add(ContainerDefinition $definition): void
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

	public function get(string $id): ?ContainerDefinition
	{
		foreach ($this->definitions as $def) {
			if ($def->hasIdOf($id)) {
				return $def;
			}
		}

		return null;
	}
}
