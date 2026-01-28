<?php
declare(strict_types=1);

namespace Meraki\Composition\Exception;

use Meraki\Composition\Exception;
use RuntimeException;

final class CouldNotInstantiate extends RuntimeException implements Exception
{
	public function __construct(string $className)
	{
		parent::__construct("Class '{$className}' could not be instantiated");
	}
}
