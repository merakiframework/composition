<?php
declare(strict_types=1);

namespace Meraki\Composition\Exception;

use Meraki\Composition\Exception;
use Meraki\Composition\ContainerDefinition;
use InvalidArgumentException;

final class DefinitionAlreadyExists extends InvalidArgumentException implements Exception
{
	public function __construct(ContainerDefinition $definition)
	{
		parent::__construct('Definition for \'' . $definition->id . '\' already exists.');
	}
}
