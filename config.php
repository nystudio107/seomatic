<?php

/**
 * SEOmatic Configuration
 *
 * Completely optional configuration settings for SEOmatic if you want to customize some
 * of its more esoteric behavior, or just want specific control over things.
 */

return array(
    // Controls whether SEOmatic will truncate the text in <title> tags to 70 characters.
    // It is HIGHLY recommended that you leave this on, as search engines do not want
    // <title> tags to be long, and long titles won't display well on mobile either.
    "truncateTitleTags" => true,

    // SEOmatic will render the Google Analytics <script> tag and code for you, if you
    // enter a Google Analytics UID tracking code in the Site Identity settings.  It
    // does not render the <script> tag if devMode is on, but here is an additional
    // override for controlling it.
    "renderGoogleAnalyticsScript" => true,
);
?>