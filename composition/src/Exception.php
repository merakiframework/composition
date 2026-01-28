<?php
declare(strict_types=1);

namespace Meraki\Composition;

use Psr\Container\ContainerExceptionInterface as PsrContainerException;
use Throwable;

interface Exception extends PsrContainerException
{
	// public array $chain;

	// public function __construct(string $message, array $chain = [], ?Throwable $previous = null)
	// {
	// 	$this->chain = $chain;

	// 	parent::__construct($message, 0, $previous);
	// }

	// public function getResolutionChainAsString(): string
	// {
	// 	return implode(' -> ', $this->chain);
	// }
}
