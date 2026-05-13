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
 * An array of expected values.
 *
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-require-implements ValuesInterface
 * @psalm-require-extends AbstractValues
 */
trait AbstractValuesTrait
{
    /**
     * @param array|\Traversable $array
     *
     * @psalm-param array|\Traversable<array-key,mixed> $array
     */
    public function createActualValues($array = []): ValuesInterface
    {
        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }
        return $this->setupAbstractValues(new ActualValues($array));
    }

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param array|\Traversable<array-key,mixed> $array
     */
    public function createExpectedValues($array = []): ValuesInterface
    {
        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }
        return $this->setupAbstractValues(new ExpectedValues($array));
    }

    protected function setupAbstractValues(AbstractValues $values): AbstractValues
    {
        $values->tag = $this->tag;
        return $values;
    }
}

// vim: syntax=php sw=4 ts=4 et:
