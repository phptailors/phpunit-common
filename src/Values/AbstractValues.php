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
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<array-key,mixed>
 */
abstract class AbstractValues extends \ArrayObject implements ValuesInterface
{
    public const TAGSIZE = 20;

    /**
     * @var ?string
     *
     * @psalm-var ?non-empty-string
     */
    protected $tag;

    /**
     * @psalm-return non-empty-string
     */
    final public static function abstractValuesTag(): string
    {
        /** @psalm-var ?non-empty-string */
        static $tag = null;

        if (null === $tag) {
            try {
                $hex = bin2hex(random_bytes(self::TAGSIZE));
            } catch (\Exception $_e) {
                // @codeCoverageIgnoreStart
                $hex = 'b431aa5424c80003a46c769389f28d0bcde7bf21';
                // @codeCoverageIgnoreEnd
            }
            $tag = "abstract-values:{$hex}";
        }
        return $tag;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function tag(): string
    {
        return $this->tag ?? self::abstractValuesTag();
    }
}

// vim: syntax=php sw=4 ts=4 et:
