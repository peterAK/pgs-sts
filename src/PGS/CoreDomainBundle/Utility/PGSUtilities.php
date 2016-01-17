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

class PGSUtilities
{
    /**
     * Slugify text into lowercase
     *
     * @param $text
     *
     * @return mixed|string
     */
    static public function slugger($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /**
     * @param $string
     *
     * @return null|string
     */
    static public function canonicalizer($string)
    {
        return null === $string ? null : mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string));
    }

    /**
     * @param $string
     * @param bool $number
     *
     * @return string
     */
    static public function randomizer($string, $number = true)
    {
        if ($number) {
            return $string . "-" . self::randomNumber();
        } else {
            return $string . "-" . self::randomCharacter();
        }
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected static function randomNumber($length = 8)
    {
        $randomized = "";

        // define possible characters
        $possible = "0123456789";

        // set up a counter
        $i = 0;

        // add random characters to $randomized until $length is reached
        for ($i = 0; $i < $length; $i++) {
            // pick a random character from the possible ones
            $randomized .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        }

        // done!
        return $randomized;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected static function randomCharacter($length = 8)
    {
        $randomized = "";

        // define possible characters
        $possible = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        // set up a counter
        $i = 0;

        // add random characters to $password until $length is reached
        for ($i = 0; $i < $length; $i++) {
            // pick a random character from the possible ones
            $randomized .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        }

        // done!
        return $randomized;
    }
}
