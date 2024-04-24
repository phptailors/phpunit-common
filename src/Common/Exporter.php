<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use SebastianBergmann\Exporter\Exporter as SebastianExporter;
use SebastianBergmann\RecursionContext\Context;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class Exporter
{
    /**
     * @psalm-param mixed $value
     *
     * @psalm-suppress MixedInferredReturnType
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    public static function export($value, bool $exportObjects = false): string
    {
        // @codeCoverageIgnoreStart

        if (class_exists(\PHPUnit\Util\Exporter::class)) {
            /**
             * @psalm-suppress InternalClass
             * @psalm-suppress InternalMethod
             * @psalm-suppress MixedReturnStatement
             */
            return \PHPUnit\Util\Exporter::export($value, $exportObjects);
        }

        if (self::isExportable($value) || $exportObjects) {
            return (new SebastianExporter())->export($value);
        }

        return '{enable export of objects to see this value}';
        // @codeCoverageIgnoreEnd
    }

    /**
     * @psalm-param mixed $value
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    private static function isExportable(&$value, ?Context $context = null): bool
    {
        // @codeCoverageIgnoreStart

        if (is_scalar($value) || null === $value) {
            return true;
        }

        if (!is_array($value)) {
            return false;
        }

        if (!$context) {
            $context = new Context();
        }

        if (false !== $context->contains($value)) {
            return true;
        }

        $array = $value;
        $context->add($value);

        foreach ($array as &$_value) {
            if (!self::isExportable($_value, $context)) {
                return false;
            }
        }

        return true;
        // @codeCoverageIgnoreEnd
    }
}

// vim: syntax=php sw=4 ts=4 et: