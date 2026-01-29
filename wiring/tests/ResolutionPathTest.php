<?php
declare(strict_types=1);

namespace Meraki\Wiring\Test;

use Meraki\Wiring\ResolutionPath;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Countable;

#[CoversClass(ResolutionPath::class)]
final class ResolutionPathTest extends TestCase
{
	#[Test]
	public function it_can_be_counted(): void
	{
		$path = new ResolutionPath();

		$this->assertInstanceOf(Countable::class, $path);
		$this->assertCount(0, $path);
	}
}
