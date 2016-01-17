<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Utility;

use Stringy\StaticStringy;
use StringTemplate\Engine;

/**
 * S, extension point for add features to Stringy, and also to make easier to use making the name of class just
 * an S.
 */
abstract class S extends StaticStringy
{
    /**
     * @param string $template      The template string
     * @param string|array $value   The value the template will be rendered with
     *
     * @return string The rendered template
     */
    public static function render($template, $value)
    {
        return (new Engine())->render($template, $value);
    }

    /**
     * Splits a text by a delimiter into an array.
     *
     * @param string $string
     * @param string $delimiter
     *
     * @return array
     */
    public static function split($string, $delimiter = ' ')
    {
        return explode($delimiter, $string);
    }

    /**
     * Given an array will join into a string using the given glue.
     *
     * @param array  $pieces
     * @param string $glue
     *
     * @return string
     */
    public static function join(array $pieces, $glue = ' ')
    {
        return implode($glue, $pieces);
    }

    /**
     * Returns the best possible representation to a string.
     *
     * @param mixed $value
     * @param mixed $maxLength
     *
     * @return string
     */
    public static function toString($value, $maxLength = 50)
    {
        $type = gettype($value);

        switch ($type)
        {
            case 'boolean':
                $str = $value ? 'true' : 'false';
                break;
            case 'integer':
            case 'double':
            case 'string':
                $str = (string) $value;
                break;
            case 'array':
                $str = json_encode($value);
                break;
            case 'object':
                $str = method_exists($value, '__toString') ? (string) $value : get_class($value);
                break;
            default:
                // @codeCoverageIgnoreStart
                $str = $type;
            // @codeCoverageIgnoreEnd
        }

        return S::substr($str, 0, $maxLength);
    }
}
