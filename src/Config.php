<?php
declare(strict_types=1);

namespace Meraki\Container;

use Meraki\Container\Definition;
use Meraki\Container\DefinitionList;

final class Config
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
