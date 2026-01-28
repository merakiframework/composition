<?php
declare(strict_types=1);

namespace Meraki\Composition\Test;

use Meraki\Composition\Test\Fixture;
use Meraki\Composition\ContainerConfig;
use Meraki\Composition\Container;
use Meraki\Composition\Exception;
use Psr\Container\ContainerInterface as PsrContainer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use RuntimeException;
use stdClass;

#[CoversClass(Container::class)]
final class ContainerTest extends TestCase
{
	#[Test]
	public function it_always_resolves_a_psr_container_interface_to_itself(): void
	{
		$container = new Container(new ContainerConfig());
		$psrContainer = $container->get(PsrContainer::class);

		$this->assertSame(
			$container,
			$psrContainer,
			'The container should return itself when asked for the PSR ContainerInterface'
		);
	}

	#[Test]
	public function it_resolves_its_configuration(): void
	{
		$config = new ContainerConfig();
		$container = new Container($config);
		$resolvedConfig = $container->get(ContainerConfig::class);
		$this->assertSame(
			$config,
			$resolvedConfig,
			'The container should return its configuration when asked for ContainerConfig'
		);
	}

	#[Test]
	public function it_throws_when_entry_is_not_found(): void
	{
		$container = new Container(new ContainerConfig());

		$this->expectException(Exception\CouldNotResolve::class);

		$container->get('NonExistentService');
	}

	#[Test]
	public function it_throws_when_implicit_definition_cannot_be_instantiated(): void
	{
		$container = new Container(new ContainerConfig());

		$this->expectException(Exception\CouldNotInstantiate::class);

		$container->get(Fixture\AbstractService::class);
	}

	#[Test]
	public function it_throws_when_explicit_definition_cannot_be_instantiated(): void
	{
		$config = new ContainerConfig();
		$config->define(Fixture\SomeInterface::class, Fixture\SomeInterface::class);

		$container = new Container($config);

		$this->expectException(Exception\CouldNotInstantiate::class);

		$container->get(Fixture\SomeInterface::class);
	}

	#[Test]
	public function it_uses_default_values_for_parameters(): void
	{
		$container = new Container(new ContainerConfig());

		$instance = $container->get(Fixture\WithDefaultValue::class);

		$this->assertInstanceOf(Fixture\WithDefaultValue::class, $instance);
		$this->assertEquals('default', $instance->name);
	}

	#[Test]
	public function it_throws_for_untyped_parameter_without_default(): void
	{
		$container = new Container(new ContainerConfig());

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Cannot resolve untyped parameter');

		$container->get(Fixture\UntypedParameter::class);
	}

	#[Test]
	public function it_throws_for_builtin_parameter_without_default(): void
	{
		$container = new Container(new ContainerConfig());

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Cannot resolve builtin parameter');

		$container->get(Fixture\BuiltinTypeWithoutDefault::class);
	}

	#[Test]
	public function it_resolves_object_typed_parameters(): void
	{
		$container = new Container(new ContainerConfig());

		$instance = $container->get(Fixture\DependsOnObject::class);

		$this->assertInstanceOf(Fixture\DependsOnObject::class, $instance);
	}

	#[Test]
	public function it_resolves_nullable_object_typed_parameters_to_null_if_definition_not_found(): void
	{
		$container = new Container(new ContainerConfig());

		$instance = $container->get(Fixture\WithNullableDependency::class);

		$this->assertInstanceOf(Fixture\WithNullableDependency::class, $instance);
	}

	#[Test]
	public function it_resolves_factory_definitions_with_parameters(): void
	{
		$config = new ContainerConfig();
		$config->define('factory_service', fn(PsrContainer $c) => $c->get(stdClass::class));
		$container = new Container($config);

		$instance = $container->get('factory_service');

		$this->assertInstanceOf(stdClass::class, $instance);
	}

	#[Test]
	public function it_does_not_override_prams_with_default_value_even_with_container_definition(): void
	{
		$config = new ContainerConfig();
		$config->define(Fixture\Dependency::class, fn() => new Fixture\SubDependencyB());
		$container = new Container($config);

		$instance = $container->get(Fixture\WithDefaultDependency::class);

		$this->assertInstanceOf(Fixture\WithDefaultDependency::class, $instance);
		$this->assertInstanceOf(Fixture\SubDependencyA::class, $instance->dep);
	}
}
