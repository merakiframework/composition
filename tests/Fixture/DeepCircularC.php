<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class DeepCircularC
{
	public function __construct(DeepCircularD $d)
	{
	}
}
