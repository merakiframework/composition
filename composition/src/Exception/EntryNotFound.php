<?php
declare(strict_types=1);

namespace Meraki\Composition\Exception;

use Meraki\Composition\Exception;
use Meraki\Composition\Definition;
use RuntimeException;

final class EntryNotFound extends RuntimeException implements Exception
{
	public function __construct(Definition $definition)
	{
		$message = 'No entry found for definition: ' . $definition->id;

		parent::__construct($message);
	}

	public static function withoutDefinition(string $id): self
	{
		return new self(new Definition($id));
	}

	public static function withDefinition(Definition $definition): self
	{
		return new self($definition);
	}
}
