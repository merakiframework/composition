<?php
declare(strict_types=1);

namespace Meraki\Composition\Exception;

use Meraki\Composition\Exception;
use Psr\Container\ContainerExceptionInterface as PsrContainerException;
use RuntimeException;

final class CircularReferenceFound extends RuntimeException implements Exception, PsrContainerException
{
	public function __construct(public readonly array $cycle)
	{
		$message = 'Circular reference found: ' . PHP_EOL;

		foreach ($cycle as $index => $id) {
			$message .= '    ';
			if ($index > 0) {
				$message .= '-> ';
			}
			$message .= $id . PHP_EOL;
		}

		parent::__construct($message);
	}
}
