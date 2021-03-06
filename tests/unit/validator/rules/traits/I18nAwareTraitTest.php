<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\tests\unit\validator\rules\traits;

use mako\i18n\I18n;
use mako\tests\TestCase;
use mako\validator\rules\RuleInterface;
use mako\validator\rules\traits\I18nAwareTrait;
use mako\validator\rules\traits\WithParametersTrait;
use mako\validator\rules\WithParametersInterface;
use Mockery;

/**
 * @group unit
 */
class I18nAwareTraitTest extends TestCase
{
	/**
	 *
	 */
	public function testCustomErrorMessage()
	{
		$rule = new class implements RuleInterface
		{
			use I18nAwareTrait;

			function validateWhenEmpty(): bool { return false; }

			public function validate($value, array $input): bool { return true; }

			public function getErrorMessage(string $field): string { return ''; }
		};

		$i18n = Mockery::mock(I18n::class);

		$i18n->shouldReceive('has')->once()->with('validate.overrides.messages.foobar.barfoo')->andReturnTrue();

		$i18n->shouldReceive('get')->once()->with('validate.overrides.messages.foobar.barfoo', ['foobar'])->andReturn('translated');

		$rule->setI18n($i18n);

		$this->assertSame('translated', $rule->getTranslatedErrorMessage('foobar', 'barfoo'));
	}

	/**
	 *
	 */
	public function testCustomErrorMessageWithParameters()
	{
		$rule = new class implements RuleInterface, WithParametersInterface
		{
			use I18nAwareTrait;
			use WithParametersTrait;

			protected $parameters = ['foo' => 'foovalue', 'bar' => 'barvalue'];

			function validateWhenEmpty(): bool { return false; }

			public function validate($value, array $input): bool { return true; }

			public function getErrorMessage(string $field): string { return ''; }
		};

		$i18n = Mockery::mock(I18n::class);

		$i18n->shouldReceive('has')->once()->with('validate.overrides.messages.foobar.barfoo')->andReturnTrue();

		$i18n->shouldReceive('get')->once()->with('validate.overrides.messages.foobar.barfoo', ['foobar', 'foovalue', 'barvalue'])->andReturn('translated');

		$rule->setI18n($i18n);

		$this->assertSame('translated', $rule->getTranslatedErrorMessage('foobar', 'barfoo'));
	}

	/**
	 *
	 */
	public function testTranslatedFieldName()
	{
		$rule = new class implements RuleInterface
		{
			use I18nAwareTrait;

			function validateWhenEmpty(): bool { return false; }

			public function validate($value, array $input): bool { return true; }

			public function getErrorMessage(string $field): string { return ''; }
		};

		$i18n = Mockery::mock(I18n::class);

		$i18n->shouldReceive('has')->once()->with('validate.overrides.messages.foobar.barfoo')->andReturnFalse();

		$i18n->shouldReceive('has')->once()->with('validate.overrides.fieldnames.foobar')->andReturnTrue();

		$i18n->shouldReceive('get')->once()->with('validate.overrides.fieldnames.foobar')->andReturn('foobaz');

		$i18n->shouldReceive('get')->once()->with('validate.barfoo', ['foobaz'])->andReturn('translated');

		$rule->setI18n($i18n);

		$this->assertSame('translated', $rule->getTranslatedErrorMessage('foobar', 'barfoo'));
	}

	/**
	 *
	 */
	public function testTranslatedFieldNames()
	{
		$rule = new class implements RuleInterface, WithParametersInterface
		{
			use I18nAwareTrait;
			use WithParametersTrait;

			protected $parameters = ['foo' => 'foovalue', 'bar' => 'barvalue'];

			protected $i18nFieldNameParameters = ['foo'];

			function validateWhenEmpty(): bool { return false; }

			public function validate($value, array $input): bool { return true; }

			public function getErrorMessage(string $field): string { return ''; }
		};

		$i18n = Mockery::mock(I18n::class);

		$i18n->shouldReceive('has')->once()->with('validate.overrides.messages.foobar.barfoo')->andReturnFalse();

		$i18n->shouldReceive('has')->once()->with('validate.overrides.fieldnames.foobar')->andReturnTrue();

		$i18n->shouldReceive('get')->once()->with('validate.overrides.fieldnames.foobar')->andReturn('foobaz');

		$i18n->shouldReceive('has')->once()->with('validate.overrides.fieldnames.foovalue')->andReturnTrue();

		$i18n->shouldReceive('get')->once()->with('validate.overrides.fieldnames.foovalue')->andReturn('foofield');

		$i18n->shouldReceive('get')->once()->with('validate.barfoo', ['foobaz', 'foofield', 'barvalue'])->andReturn('translated');

		$rule->setI18n($i18n);

		$this->assertSame('translated', $rule->getTranslatedErrorMessage('foobar', 'barfoo'));
	}

	/**
	 *
	 */
	public function testDefaultMessage()
	{
		$rule = new class implements RuleInterface
		{
			use I18nAwareTrait;

			function validateWhenEmpty(): bool { return false; }

			public function validate($value, array $input): bool { return true; }

			public function getErrorMessage(string $field): string { return ''; }
		};

		$i18n = Mockery::mock(I18n::class);

		$i18n->shouldReceive('has')->once()->with('validate.overrides.messages.foobar.barfoo')->andReturnFalse();

		$i18n->shouldReceive('has')->once()->with('validate.overrides.fieldnames.foobar')->andReturnFalse();

		$i18n->shouldReceive('get')->once()->with('validate.barfoo', ['foobar'])->andReturn('translated');

		$rule->setI18n($i18n);

		$this->assertSame('translated', $rule->getTranslatedErrorMessage('foobar', 'barfoo'));
	}
}
