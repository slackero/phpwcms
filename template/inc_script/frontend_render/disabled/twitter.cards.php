<?php
/**
 * Twitter Cards frontend render script for phpwcms
 *
 * Use it in combination with [X] social sharing checkbox to mix
 * OpenGraph meta tags with Twitter Cards Markup Tags and optional
 * HTML comment to define type of Twitter Card (e.g. template)
 * like <!--TWITTER:summary_large_image-->
 *
 * @see https://dev.twitter.com/cards/getting-started Documentation of Twitter Cards
 *
 * @author Oliver Georgi <slackero@gmail.com>
 * @copyright 2017 Oliver Georgi
 */

if(!$content['list_mode']) {

    // @username of website
    set_meta('twitter:site', '@phpwcms');

    // Define the default Twitter Card type
    $twitter_card = 'summary';

    // Search template for specific card property
    if(preg_match('/<!--TWITTER:(.*?)-->/', $content['all'], $match_twitter_cards)) {

        // possible Twitter card values
        $twitter_cards = array(
            'summary'             => '',
            'summary_large_image' => '',
            'photo'               => '',
            'gallery'             => '',
            'product'             => '',
            'app'                 => '',
            'player'              => '',
        );
        if(($match_twitter_cards = trim(strtolower($match_twitter_cards[1]))) && isset($twitter_cards[ $match_twitter_cards ])) {
            $twitter_card = $match_twitter_cards;
        }
    }

    set_meta('twitter:card', $twitter_card);

    // Use the article username if matching a Twitter user ID
    if($content['article_username'] && substr($content['article_username'], 0, 1) === '@') {

        set_meta('twitter:creator', $content['article_username']);

    }

    /**
     * There are much more Twitter Card Markup Tags
     * @see https://dev.twitter.com/cards/markup Cards Markup Tag Reference
     */

}