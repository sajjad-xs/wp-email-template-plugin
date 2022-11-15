<?php

namespace WPEmailKit\Helpers;

defined('ABSPATH') || exit;

/**
 * @package  WPEmailKitPlugin
 */


class Utils
{

    public static function get_kses_array()
    {
        return array(
            'html'                          => array(),
            'head'                          => array(),
            'body'                          => array(),
            'a'                             => array(
                'class'  => array(),
                'href'   => array(),
                'rel'    => array(),
                'title'  => array(),
                'target' => array(),
                'style'  => array(),
            ),
            'abbr'                          => array(
                'title' => array(),
                'style'  => array(),
            ),
            'b'                             => array(
                'class' => array(),
                'style'  => array(),
            ),
            'blockquote'                    => array(
                'cite' => array(),
                'style'  => array(),
            ),
            'cite'                          => array(
                'title' => array(),
                'style'  => array(),
            ),
            'code'                          => array(
                'style'  => array(),
            ),
            'pre'                           => array(
                'style'  => array(),
            ),
            'del'                           => array(
                'datetime' => array(),
                'title'    => array(),
                'style'  => array(),
            ),
            'dd'                            => array(
                'style'  => array(),
            ),
            'div'                           => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl'                            => array(
                'style' => array(),
            ),
            'dt'                            => array(
                'style' => array(),
            ),
            'em'                            => array(
                'style' => array(),
            ),
            'strong'                        => array(
                'style' => array(),
            ),
            'h1'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h2'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h3'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h4'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h5'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h6'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'i'                             => array(
                'class' => array(),
                'style' => array(),
            ),
            'img'                           => array(
                'alt'        => array(),
                'class'        => array(),
                'height'    => array(),
                'src'        => array(),
                'width'        => array(),
                'style'        => array(),
                'title'        => array(),
                'srcset'    => array(),
                'loading'    => array(),
                'sizes'        => array(),
                'style' => array(),
            ),
            'figure'                        => array(
                'class'        => array(),
                'style' => array(),
            ),
            'li'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'ol'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'p'                             => array(
                'class' => array(),
                'style' => array(),
            ),
            'q'                             => array(
                'cite'  => array(),
                'title' => array(),
                'style' => array(),
            ),
            'span'                          => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'iframe'                        => array(
                'width'       => array(),
                'height'      => array(),
                'scrolling'   => array(),
                'frameborder' => array(),
                'allow'       => array(),
                'src'         => array(),
                'style' => array(),
            ),
            'strike'                        => array(),
            'br'                            => array(),
            'table'                         => array(),
            'thead'                         => array(),
            'tbody'                         => array(),
            'tfoot'                         => array(),
            'tr'                            => array(),
            'th'                            => array(),
            'td'                            => array(),
            'colgroup'                      => array(),
            'col'                           => array(),
            'strong'                        => array(),
            'data-wow-duration'             => array(),
            'data-wow-delay'                => array(),
            'data-wallpaper-options'        => array(),
            'data-stellar-background-ratio' => array(),
            'ul'                            => array(
                'class' => array(),
            ),
            'svg'                           => array(
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true, // <= Must be lower case!
                'preserveaspectratio' => true,
            ),
            'g'                             => array('fill' => true),
            'title'                         => array('title' => true),
            'path'                          => array(
                'd'    => true,
                'fill' => true,
            ),
            'input'                            => array(
                'class'        => array(),
                'type'        => array(),
                'value'        => array()
            )
        );
    }

    public static function kses($raw)
    {

        $allowed_tags = self::get_kses_array();

        if (function_exists('wp_kses')) { // WP is here
            return wp_kses($raw, $allowed_tags);
        } else {
            return $raw;
        }
    }
}
