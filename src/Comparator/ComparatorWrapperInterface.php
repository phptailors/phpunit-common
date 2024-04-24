<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Comparator;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ComparatorWrapperInterface
{
    /**
     * Returns an instance of ComparatorInterface.
     */
    public function getComparator(): ComparatorInterface;
}

// vim: syntax=php sw=4 ts=4 et:
