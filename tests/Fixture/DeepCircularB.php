<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class DeepCircularB
{
	public function __construct(DeepCircularC $c)
	{
	}
}
