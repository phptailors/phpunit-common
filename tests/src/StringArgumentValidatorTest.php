<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(StringArgumentValidator::class)]
final class StringArgumentValidatorTest extends TestCase
{
    public function testValidateSucceeds(): void
    {
        $validator = new StringArgumentValidator('is_numeric', 'a numeric string');
        self::assertNull($validator->validate(1, '123'));
    }

    public function testValidateThrowsInvalidArgumentException(): void
    {
        $message = sprintf('Argument 1 passed to %s() must be a numeric string, \'foo\' given.', __METHOD__);

        $validator = new StringArgumentValidator('is_numeric', 'a numeric string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $validator->validate(1, 'foo');
    }
}

// vim: syntax=php sw=4 ts=4 et:
