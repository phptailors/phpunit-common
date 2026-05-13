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
    private $tag;

    /**
     * @var ?string
     *
     * @psalm-var ?non-empty-string
     */
    private static $abstractValuesTag;

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param ?non-empty-string $tag
     */
    final public function __construct($array = [], ?string $tag = null)
    {
        $this->tag = $tag;

        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }

    /**
     * @psalm-return non-empty-string
     */
    final public static function abstractValuesTag(): string
    {
        if (null === self::$abstractValuesTag) {
            // @codeCoverageIgnoreStart
            try {
                $hex = bin2hex(random_bytes(self::TAGSIZE));
            } catch (\Exception $_e) {
                $hex = 'b431aa5424c80003a46c769389f28d0bcde7bf21';
            }
            self::$abstractValuesTag = "abstract-values:{$hex}";
            // @codeCoverageIgnoreEnd
        }

        return self::$abstractValuesTag;
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function tag(): string
    {
        return $this->tag ?? self::abstractValuesTag();
    }

    /**
     * @param array|\Traversable $array
     */
    final public function createActualValues($array = []): ValuesInterface
    {
        return new ActualValues($array, $this->tag);
    }

    /**
     * @param array|\Traversable $array
     */
    final public function createExpectedValues($array = []): ValuesInterface
    {
        return new ExpectedValues($array, $this->tag);
    }
}

// vim: syntax=php sw=4 ts=4 et:
