<?php
namespace Craft;

class SeomaticVariable
{

/* ================================================================================
    EXTERNAL methods for templating
================================================================================ */

/* --------------------------------------------------------------------------------
    Render a generic JSON-LD object, passed in as an array() in the format:

    PHP:

    $myJSONLD = array(
        "type" => "Corporation",
        "name" => "nystudio107",
        "sameAs" => ["https://Twitter.com/nystudio107","https://plus.google.com/+nystudio107"],
        "address" => array(
            "type" => 'PostalAddress',
            "addressCountry" => "USA",
            ),
        );

    Twig:

    {% set myJSONLD = {
        "type": "Corporation",
        "name": "nystudio107",
        "sameAs": ["https://Twitter.com/nystudio107","https://plus.google.com/+nystudio107"],
        "address": {
            "type": 'PostalAddress',
            "addressCountry": "USA",
        },
    } %}

    The array can be nested arbitrarily deep with sub-arrays.  The first key in
    the array, and in each sub-array, should be an "type" with a valid
    Schema.org type as the value.  Because Twig doesn't support array keys with
    non-alphanumeric characters, SEOmatic transforms the keys "type" into "@type"
    at render time.
-------------------------------------------------------------------------------- */

    public function renderJSONLD($object=array())
    {
        craft()->seomatic->sanitizeArray($object);
        $result = craft()->seomatic->renderJSONLD($object);

        return TemplateHelper::getRaw(rtrim($result));
    } /* -- renderJSONLD */

/* --------------------------------------------------------------------------------
    Extract the most important words from the passed in text via TextRank
-------------------------------------------------------------------------------- */

    public function extractKeywords($text = null, $limit = 15, $withoutStopWords = true)
    {
        $result = craft()->seomatic->extractKeywords($text, $limit, $withoutStopWords);

        return $result;
    } /* -- extractKeywords */

/* --------------------------------------------------------------------------------
    Extract a summary from the text, or if it's not long enough, just return the text
-------------------------------------------------------------------------------- */

    public function extractSummary($text = null, $limit = null, $withoutStopWords = true)
    {
        $result = craft()->seomatic->extractSummary($text, $limit, $withoutStopWords);

        return $result;
    } /* -- extractSummary */

/* --------------------------------------------------------------------------------
    Truncate the the string passed in, breaking it on a word.  $desiredLength
    is in characters; the returned string will be broken on a whole-word
    boundary, with an … appended to the end if it is truncated
-------------------------------------------------------------------------------- */

    public function truncateStringOnWord($theString, $desiredLength)
    {
        $result = craft()->seomatic->truncateStringOnWord($theString, $desiredLength);

        return $result;
    } /* -- truncateStringOnWord */

/* --------------------------------------------------------------------------------
    Encode an email address as ordinal values to obfuscate it to bots
-------------------------------------------------------------------------------- */

    public function encodeEmailAddress($emailAddress)
    {
        $result = craft()->seomatic->encodeEmailAddress($emailAddress);

        return $result;
    } /* -- encodeEmailAddress */

/* --------------------------------------------------------------------------------
    Extract all of the text and rich text from the fields in MatrixBlockModels
-------------------------------------------------------------------------------- */

    public function extractTextFromMatrix($matrixBlocks)
    {
        $result = craft()->seomatic->extractTextFromMatrix($matrixBlocks);

        return $result;
    } /* -- extractTextFromMatrix */

/* --------------------------------------------------------------------------------
    Get a fully qualified URL based on the siteUrl, if no scheme/host is present
-------------------------------------------------------------------------------- */

    public function getFullyQualifiedUrl($url)
    {
        $result = craft()->seomatic->getFullyQualifiedUrl($url);

        return $result;
    } /* -- getFullyQualifiedUrl */

/* ================================================================================
    INTERNAL methods for SEOmatic use
================================================================================ */

/* --------------------------------------------------------------------------------
    Render the SEOmatic template
-------------------------------------------------------------------------------- */

    function render($templatePath="", $metaVars=null)
    {
        if ($metaVars)
            $result = craft()->seomatic->render($templatePath, $metaVars);
        else
            $result = craft()->seomatic->render($templatePath);

        return rtrim($result);
    } /* -- render */

/* --------------------------------------------------------------------------------
    Render the SEOmatic preview template
-------------------------------------------------------------------------------- */

    function renderPreview($templatePath="", $forTemplate="", $elementId=null, $locale=null)
    {
        $entryMeta = null;

        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals($forTemplate, $locale);

/* -- Fudge the canonicalUrl for the preview */

        if (!$entryMeta)
        {
            $siteUrl = craft()->getSiteUrl();
            if (($siteUrl[strlen($siteUrl) -1] != '/') && $forTemplate)
            {
                $siteUrl = $siteUrl + '/';
            }
            $fullUrl = $siteUrl . $forTemplate;
            $metaVars['seomaticMeta']['canonicalUrl'] = $fullUrl;
            if (isset($metaVars['seomaticMeta']['og']))
                $metaVars['seomaticMeta']['og']['url'] = $fullUrl;
        }

        $result = craft()->seomatic->render($templatePath, $metaVars, true);

        return rtrim($result);
    } /* -- renderPreview */

/* --------------------------------------------------------------------------------
    Render the SEOmatic display preview template
-------------------------------------------------------------------------------- */

    function renderDisplayPreview($templateName="", $forTemplate="", $elementId=null, $locale=null)
    {
        $entryMeta = null;

        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals($forTemplate, $locale);

/* -- Fudge the canonicalUrl for the preview */

        if (!$entryMeta)
        {
            $siteUrl = craft()->getSiteUrl();
            if (($siteUrl[strlen($siteUrl) -1] != '/') && $forTemplate)
            {
                $siteUrl = $siteUrl + '/';
            }
            $fullUrl = $siteUrl . $forTemplate;
            $metaVars['seomaticMeta']['canonicalUrl'] = $fullUrl;
            if (isset($metaVars['seomaticMeta']['og']))
                $metaVars['seomaticMeta']['og']['url'] = $fullUrl;
        }

        $result = craft()->seomatic->renderDisplayPreview($templateName, $metaVars);

        return rtrim($result);
    } /* -- renderDisplayPreview */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Identity template
-------------------------------------------------------------------------------- */

    function renderIdentity($elementId=null, $locale=null, $isPreview=false)
    {
        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $result = craft()->seomatic->renderIdentity($metaVars, $locale, $isPreview);

        return rtrim($result);
    } /* -- renderIdentity */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Website template
-------------------------------------------------------------------------------- */

    function renderWebsite($elementId=null, $locale=null, $isPreview=false)
    {
        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $result = craft()->seomatic->renderWebsite($metaVars, $locale, $isPreview);

        return rtrim($result);
    } /* -- renderWebsite */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Product template
-------------------------------------------------------------------------------- */

    function renderProduct($elementId=null, $locale=null, $isPreview=false)
    {
        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $result = craft()->seomatic->renderProduct($metaVars, $locale, $isPreview);

        return rtrim($result);
    } /* -- renderProduct */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Place template
-------------------------------------------------------------------------------- */

    function renderPlace($elementId=null, $locale=null, $isPreview=false)
    {
        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $result = craft()->seomatic->renderPlace($metaVars, $locale, $isPreview);

        return rtrim($result);
    } /* -- renderPlace */

/* --------------------------------------------------------------------------------
    Render the Google Analytics <script> tags
-------------------------------------------------------------------------------- */

    function renderGoogleAnalytics($elementId=null, $locale=null, $isPreview=false)
    {
        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $result = craft()->seomatic->renderGoogleAnalytics($metaVars, $locale, $isPreview);

        return rtrim($result);
    } /* -- renderGoogleAnalytics */

/* --------------------------------------------------------------------------------
    Render the SEOmatic globals for the preview
-------------------------------------------------------------------------------- */

    function renderGlobals($forTemplate="", $elementId=null, $locale=null)
    {
        $entryMeta = null;

        if (!$locale)
            $locale = craft()->language;

        if ($elementId)
        {
            $element = craft()->elements->getElementById($elementId, null, $locale);
            if ($element)
            {
                $entryMeta = craft()->seomatic->getMetaFromElement($element);
                if ($entryMeta)
                    craft()->seomatic->setEntryMeta($entryMeta, $element->url);
            }
        }

        $metaVars = craft()->seomatic->getGlobals($forTemplate, $locale);

/* -- Fudge the canonicalUrl for the preview */

        if (!$entryMeta)
        {
            $siteUrl = craft()->getSiteUrl();
            if (($siteUrl[strlen($siteUrl) -1] != '/') && $forTemplate)
            {
                $siteUrl = $siteUrl + '/';
            }
            $fullUrl = $siteUrl . $forTemplate;
            $metaVars['seomaticMeta']['canonicalUrl'] = $fullUrl;
            if (isset($metaVars['seomaticMeta']['og']))
                $metaVars['seomaticMeta']['og']['url'] = $fullUrl;
        }

/* -- No need to expose the locale */

        unset($metaVars['seomaticIdentity']['locale']);
        unset($metaVars['seomaticSocial']['locale']);
        unset($metaVars['seomaticSiteMeta']['locale']);
        unset($metaVars['seomaticCreator']['locale']);

        $result = craft()->seomatic->renderGlobals($metaVars, $forTemplate);

        return rtrim($result);
    } /* -- renderGlobals */

/* --------------------------------------------------------------------------------
    Render the humans.txt template
-------------------------------------------------------------------------------- */

    public function renderHumans($isPreview=false)
    {
        $result = craft()->seomatic->renderHumans($isPreview);

        return $result;
    } /* -- renderHumans */

/* --------------------------------------------------------------------------------
    Render the humans.txt user-defined template
-------------------------------------------------------------------------------- */

    public function renderHumansTemplate()
    {
        $result = craft()->seomatic->renderHumansTemplate();

        return $result;
    } /* -- renderHumansTemplate */

/* --------------------------------------------------------------------------------
    Render the robots.txt template
-------------------------------------------------------------------------------- */

    public function renderRobots($isPreview=false)
    {
        $result = craft()->seomatic->renderRobots($isPreview);

        return $result;
    } /* -- renderRobots */

/* --------------------------------------------------------------------------------
    Render the robots.txt user-defined template
-------------------------------------------------------------------------------- */

    public function renderRobotsTemplate()
    {
        $result = craft()->seomatic->renderRobotsTemplate();

        return $result;
    } /* -- renderRobotsTemplate */

/* --------------------------------------------------------------------------------
    Get the identity record
-------------------------------------------------------------------------------- */

    public function getIdentity()
    {
        return craft()->seomatic->getIdentity();
    } /* -- getIdentity */

/* --------------------------------------------------------------------------------
    Get the social record
-------------------------------------------------------------------------------- */

    public function getSocial()
    {
        return craft()->seomatic->getSocial();
    } /* -- getSocial */

/* --------------------------------------------------------------------------------
    Get the plugin name
-------------------------------------------------------------------------------- */

    function getPluginName()
    {
        $retourPlugin = craft()->plugins->getPlugin('seomatic');
        $result = $retourPlugin->getName();
        return $result;
    } /* -- getPluginName */

} /* -- class SeomaticVariable */