<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use Tailors\PHPUnit\CircularDependencyException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template ConstraintClass of Constraint
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @param mixed $args
     */
    abstract public static function createConstraint(...$args): Constraint;

    /**
     * Returns constraint's class name.
     *
     * @psalm-return class-string<ConstraintClass>
     *
     * @psalm-pure
     */
    abstract public static function getConstraintClass(): string;

    /**
     * @param array $args arguments for createConstraint()
     *
     * @psalm-return ConstraintClass
     *
     * @throws Exception
     * @throws ExpectationFailedException
     */
    final public function examineCreateConstraint(array $args): Constraint
    {
        $constraint = $this->createConstraint(...$args);
        $this->assertInstanceOf(static::getConstraintClass(), $constraint);

        return $constraint;
    }

    /**
     * Tests whether the constraint throws expected exception with expected
     * message when wrapped with an unary operator.
     *
     * @param array  $args    arguments for createConstraint()
     * @param mixed  $actual  actual value that shall cause the constraint to fail
     * @param string $message expected exception message
     *
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    final public function examineConstraintUnaryOperatorFailure(array $args, $actual, string $message): void
    {
        $constraint = $this->createConstraint(...$args);

        $unary = $this->wrapWithUnaryOperator($constraint);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertThat($actual, $unary);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param array $args   arguments passed to createConstraint()
     * @param mixed $actual actual value
     *
     * @throws ExpectationFailedException
     */
    final public function examineConstraintMatchSucceeds(array $args, $actual): void
    {
        $constraint = $this->createConstraint(...$args);
        self::assertThat($actual, $constraint);
    }

    /**
     * @param array  $args    arguments passed to createConstraint()
     * @param mixed  $actual
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws CircularDependencyException
     */
    final public function examineConstraintMatchFails(array $args, $actual, string $message): void
    {
        $constraint = $this->createConstraint(...$args);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param array $args   arguments passed to createConstraint()
     * @param mixed $actual
     *
     * @throws ExpectationFailedException
     */
    final public function examineNotConstraintMatchSucceeds(array $args, $actual): void
    {
        $constraint = self::logicalNot($this->createConstraint(...$args));
        self::assertThat($actual, $constraint);
    }

    /**
     * @param array  $args    arguments passed to createConstraint()
     * @param mixed  $actual
     * @param string $message
     *
     * @throws ExpectationFailedException
     */
    final public function examineNotConstraintMatchFails(array $args, $actual, string $message): void
    {
        $constraint = self::logicalNot($this->createConstraint(...$args));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * Returns $constraint wrapped with an UnaryOperator.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws ReflectionException
     */
    final protected function wrapWithUnaryOperator(
        Constraint $constraint,
        string $operator = 'noop',
        int $precedence = 1
    ): UnaryOperator {
        return new class($constraint, $operator, $precedence) extends UnaryOperator {
            public function __construct(Constraint $constraint, private string $operator, private int $precedence)
            {
                parent::__construct($constraint);
            }

            public function operator(): string
            {
                return $this->operator;
            }

            public function precedence(): int
            {
                return $this->precedence;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
