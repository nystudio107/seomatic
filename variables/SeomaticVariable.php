<?php
namespace Craft;

class SeomaticVariable
{

/* ================================================================================
    EXTERNAL methods for templating
================================================================================ */

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

    function renderPreview($templatePath="", $forTemplate="", $locale=null)
    {
	    if (!$locale)
	    	$locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals($forTemplate, $locale);

/* -- Fudge the canonicalUrl for the preview */

        $siteUrl = craft()->getSiteUrl();
        if (($siteUrl[strlen($siteUrl) -1] != '/') && $forTemplate)
        {
            $siteUrl = $siteUrl + '/';
        }
        $fullUrl = $siteUrl . $forTemplate;
        $metaVars['seomaticMeta']['canonicalUrl'] = $fullUrl;

        $result = craft()->seomatic->render($templatePath, $metaVars, true);
        
        return rtrim($result);
    } /* -- renderPreview */

/* --------------------------------------------------------------------------------
    Render the SEOmatic display preview template
-------------------------------------------------------------------------------- */

    function renderDisplayPreview($templateName="", $forTemplate="", $locale=null)
    {
	    if (!$locale)
	    	$locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals($forTemplate, $locale);

/* -- Fudge the canonicalUrl for the preview */

        $siteUrl = craft()->getSiteUrl();
        if (($siteUrl[strlen($siteUrl) -1] != '/') && $forTemplate)
        {
            $siteUrl = $siteUrl + '/';
        }
        $fullUrl = $siteUrl . $forTemplate;
        $metaVars['seomaticMeta']['canonicalUrl'] = $fullUrl;

        $result = craft()->seomatic->renderDisplayPreview($templateName, $metaVars);
        
        return rtrim($result);
    } /* -- renderDisplayPreview */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Identity template
-------------------------------------------------------------------------------- */

    function renderIdentity($templatePath="", $isPreview=false, $locale=null)
    {
	    if (!$locale)
	    	$locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $result = craft()->seomatic->renderIdentity($templatePath, $isPreview, $metaVars, $locale);
        
        return rtrim($result);
    } /* -- renderIdentity */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Website template
-------------------------------------------------------------------------------- */

    function renderWebsite($templatePath="", $isPreview=false, $locale=null)
    {
	    if (!$locale)
	    	$locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $result = craft()->seomatic->renderWebsite($templatePath, $isPreview, $metaVars, $locale);
        
        return rtrim($result);
    } /* -- renderWebsite */

/* --------------------------------------------------------------------------------
    Render the SEOmatic globals for the preview
-------------------------------------------------------------------------------- */

    function renderGlobals($forTemplate="", $locale=null)
    {

	    if (!$locale)
	    	$locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals($forTemplate, $locale);

/* -- Fudge the canonicalUrl for the preview */

        $siteUrl = craft()->getSiteUrl();
        if (($siteUrl[strlen($siteUrl) -1] != '/') && $forTemplate)
        {
            $siteUrl = $siteUrl + '/';
        }
        $fullUrl = $siteUrl . $forTemplate;
        $metaVars['seomaticMeta']['canonicalUrl'] = $fullUrl;

/* -- No need to expose the locale */

        unset($metaVars['seomaticIdentity']['locale']);
        unset($metaVars['seomaticSocial']['locale']);
        unset($metaVars['seomaticSiteMeta']['locale']);
        unset($metaVars['seomaticCreator']['locale']);

        $result = craft()->seomatic->renderGlobals($metaVars, $forTemplate);
        
        return rtrim($result);
    } /* -- renderGlobals */

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

} /* -- class SeomaticVariable */