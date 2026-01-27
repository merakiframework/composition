<?php
declare(strict_types=1);

namespace Meraki\Container\Exception;

use Meraki\Container\Exception;
use Meraki\Container\Definition;
use InvalidArgumentException;

final class DefinitionAlreadyExists extends InvalidArgumentException implements Exception
{
	public function __construct(Definition $definition)
	{
		parent::__construct('Definition for \'' . $definition->id . '\' already exists.');
	}
}
