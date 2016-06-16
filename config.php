<?php
/**
 * SEOmatic Configuration
 *
 * Completely optional configuration settings for SEOmatic if you want to customize some
 * of its more esoteric behavior, or just want specific control over things.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'seomatic.php' and make
 * your changes there.
 */

return array(
/**
 * The maximum number of characters allow for the seoTitle.  It's HIGHLY recommend that
 * you keep this set to 70 characters.
 */
    "maxTitleLength" => 70,

/**
 * Controls whether SEOmatic will truncate the text in <title> tags maxTitleLength characters.
 * It is HIGHLY recommended that you leave this on, as search engines do not want
 * <title> tags to be long, and long titles won't display well on mobile either.
 */
    "truncateTitleTags" => true,

/**
 * The maximum number of characters allow for the seoDescription.  It's HIGHLY recommend that
 * you keep this set to 160 characters.
 */
    "maxDescriptionLength" => 160,

/**
 * Controls whether SEOmatic will truncate the descrption tags maxDescriptionLength characters.
 * It is HIGHLY recommended that you leave this on, as search engines do not want
 * description tags to be long.
 */
    "truncateDescriptionTags" => true,

/**
 * The maximum number of characters allow for the seoKeywords.  It's HIGHLY recommend that
 * you keep this set to 200 characters.
 */
    "maxKeywordsLength" => 200,

/**
 * Controls whether SEOmatic will truncate the keywords tags maxKeywordsLength characters.
 * It is HIGHLY recommended that you leave this on, as search engines do not want
 * keywords tags to be long.
 */
    "truncateKeywordsTags" => true,

/**
 * SEOmatic will render the Google Analytics <script> tag and code for you, if you
 * enter a Google Analytics UID tracking code in the Site Identity settings.  It
 * does not render the <script> tag if devMode is on, but here is an additional
 * override for controlling it.
 */
    "renderGoogleAnalyticsScript" => true,

/**
 * SEOmatic will render Product JSON-LD microdata for you automatically, if an SEOmatic Meta
 * FieldType is attached to a Craft Commerce Product.  Set this to false to override
 * this behavior, and not render the Product JSON-LD microdata.
 */
    "renderCommerceProductJSONLD" => true,

/**
 * SEOmatic uses the `siteUrl` to generate the external URLs.  If you are using it in
 * a non-standard environment, such as a headless ElementAPI server, you can override
 * what it uses for the `siteUrl` below.
 */
    "siteUrlOverride" => '',

/**
 * Controls whether SEOmatic will display the SEOmetrics information during Live Preview.
 */
    "displaySeoMetrics" => true,

/**
 * Determines the string prepended to the <title> tag when devMode is on.
 */
    "siteDevModeTitle" => '[devMode]',

);