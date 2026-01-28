<?php
declare(strict_types=1);

namespace Meraki\Composition;

use Meraki\Composition\Definition;
use Meraki\Composition\DefinitionList;

final class ContainerConfig
{
	public private(set) DefinitionList $definitions;

	public function __construct()
	{
		$this->definitions = new DefinitionList();
	}

	public function define(string $id, callable|string|object|null $definition = null): Definition
	{
		$definition = new Definition($id, $definition);

		$this->definitions->add($definition);

		return $definition;
	}
}
