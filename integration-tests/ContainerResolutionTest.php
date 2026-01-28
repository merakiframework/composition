<?php
declare(strict_types=1);

namespace Meraki\Integration\Test;

use Meraki\Integration\Test\Fixture;
use Meraki\Composition\Config;
use Meraki\Composition\Container;
use Meraki\Composition\Exception;
use Psr\Container\ContainerInterface as PsrContainer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use RuntimeException;
use stdClass;

#[CoversClass(Container::class)]
final class ContainerResolutionTest extends TestCase
{
	#[Test]
	public function it_throws_when_a_circular_reference_is_found(): void
	{
		$container = new Container(new Config());

		$this->expectException(Exception\CircularReferenceFound::class);

		try {
			$container->get(Fixture\CircularA::class);
		} catch (Exception\CircularReferenceFound $e) {
			$this->assertSame(
				[
					Fixture\CircularA::class,
					Fixture\CircularB::class,
					Fixture\CircularC::class,
					Fixture\CircularA::class,
				],
				$e->cycle,
				'The resolution chain should clearly show the circular reference'
			);

			$this->assertStringContainsString(
				'Circular reference found:',
				$e->getMessage()
			);

			throw $e; // rethrow so PHPUnit sees it
		}
	}

	#[Test]
	public function it_resets_the_resolution_path_between_multiple_get_calls(): void
	{
		$container = new Container(new Config());

		// First resolution: circular dependency (expected to fail)
		try {
			$container->get(Fixture\CircularA::class);
			$this->fail('Expected CircularReferenceFound to be thrown');
		} catch (Exception\CircularReferenceFound $e) {
			$this->assertSame(
				[
					Fixture\CircularA::class,
					Fixture\CircularB::class,
					Fixture\CircularC::class,
					Fixture\CircularA::class,
				],
				$e->cycle
			);
		}

		$instance = $container->get(Fixture\NonCircularA::class);

		try {
			$container->get(Fixture\CircularA::class);
			$this->fail('Expected CircularReferenceFound on second resolution');
		} catch (Exception\CircularReferenceFound $e) {
			$this->assertSame(
				[
					Fixture\CircularA::class,
					Fixture\CircularB::class,
					Fixture\CircularC::class,
					Fixture\CircularA::class,
				],
				$e->cycle
			);
		}
	}

	#[Test]
	public function it_detects_circular_references_across_nested_get_calls(): void
	{
		$container = new Container(new Config());

		$this->expectException(Exception\CircularReferenceFound::class);

		try {
			$container->get(Fixture\DeepCircularA::class);
			$this->fail('Expected CircularReferenceFound');
		} catch (Exception\CircularReferenceFound $e) {
			$this->assertSame(
				[
					Fixture\DeepCircularA::class,
					Fixture\DeepCircularB::class,
					Fixture\DeepCircularC::class,
					Fixture\DeepCircularD::class,
					Fixture\DeepCircularB::class,
				],
				$e->cycle
			);

			throw $e; // rethrow so PHPUnit sees it
		}
	}
}
