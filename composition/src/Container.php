<?php
declare(strict_types=1);

namespace Meraki\Composition;

use Meraki\Composition\ContainerDefinition;
use Meraki\Composition\FrozenContainer;
use Meraki\Wiring\ContainerDefinitionList;
use Psr\Container\ContainerInterface as PsrContainer;

final class Container
{
	private ContainerDefinitionList $definitions;

	public function __construct()
	{
		$this->definitions = new ContainerDefinitionList();
	}

	public function define(string $id, callable|string|object|null $definition = null): ContainerDefinition
	{
		$definition = new ContainerDefinition($id, $definition);

		$this->definitions->add($definition);

		return $definition;
	}

	public function freeze(): PsrContainer
	{
		return new FrozenContainer($this->definitions);
	}
}
