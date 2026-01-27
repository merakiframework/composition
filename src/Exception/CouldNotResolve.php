<?php
declare(strict_types=1);

namespace Meraki\Container\Exception;

use Meraki\Container\Exception;
use RuntimeException;
use Throwable;

final class CouldNotResolve extends RuntimeException implements Exception
{
	public function __construct(
		public readonly string $id
	) {
		parent::__construct("Could not resolve dependency: {$id}");
	}
}
