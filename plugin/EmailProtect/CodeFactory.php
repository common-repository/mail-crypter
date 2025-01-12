<?php

/**
 * Copyright (c) 2021 - 2023 CodeLeaf
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace EmailProtect;

/**
 * Class CodeFactory
 * @package EMailProtect
 */
final class CodeFactory
{

    /**
     * @param string $str
     * @return string
     */
    public static function encrypt_to_ascii($str) {
        $str = (string) $str;
        return array_reduce(
            array_map(function($piece) {
                $piece = (string) $piece;
                return ord($piece);
            }, str_split($str)),
            function($carry, $item) {
                $carry = (string) $carry;
                $item = (int) $item;
                $carry .= "&#$item;";
                return $carry;
            },
            ""
        );
    }

    /**
     * @param string $str
     * @return string
     */
    public static function encrypt_by_caesar($str) {
        $str = (string) $str;
        return implode("",
            array_map(function ($piece) {
                $piece = (string) $piece;
                return chr(ord($piece) + 2);
            }, str_split($str))
        );
    }

    /**
     * @param string $mail
     * @param string $text
     * @param bool $js
     * @return string
     */
    public static function mail_to_code($mail, $text, $js = false)
    {
        $mail = (string) $mail;
        $text = (string) $text;
        $js = (bool) $js;
        if(count(func_get_args()) > 2) {
            trigger_error(
                '"js" is deprecated and will be removed in a future release', E_USER_NOTICE
            );
        }
        $enc_mail = CodeFactory::encrypt_by_caesar($mail);
        return $text === '' ?
            "<span><a href='#' data-email-protect-click='$enc_mail'><span data-email-protect='{$enc_mail}'></span></a></span>" :
            "<span><a href='#' data-email-protect-click='$enc_mail'>$text</a></span>";
    }

}