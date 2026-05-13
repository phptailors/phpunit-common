<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * An array of actual or expected values.
 *
 * @template-extends \Traversable<array-key, mixed>
 * @template-extends \ArrayAccess<array-key, mixed>
 *
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ValuesInterface extends \Traversable, \ArrayAccess, \Countable
{
    /**
     * Returns true if this object represents actual values (as opposite to expected values).
     */
    public function actual(): bool;

    /**
     * Returns the tag for category of the values. Different tags shall be
     * returned for different categories, such as selected array values, key
     * sorted array values, object properties, class properties, etc.
     *
     * @psalm-return non-empty-string
     */
    public function tag(): string;

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param array|\Traversable<array-key,mixed> $array
     */
    public function createActualValues($array = []): ValuesInterface;

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param array|\Traversable<array-key,mixed> $array
     */
    public function createExpectedValues($array = []): ValuesInterface;
}

// vim: syntax=php sw=4 ts=4 et:
