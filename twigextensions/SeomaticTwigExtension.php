<?php 
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class SeomaticTwigExtension extends \Twig_Extension
{

/* --------------------------------------------------------------------------------
    The name of our Twig extension
-------------------------------------------------------------------------------- */

    public function getName()
    {
        return 'SEOmatic';
    }

/* --------------------------------------------------------------------------------
    Return our global variables
-------------------------------------------------------------------------------- */

    public function getGlobals()
    {   
	    $result = array();
		if (craft()->request->isSiteRequest())
		{
	        $currentTemplate = $this->_get_current_template_path();
	        $result = craft()->seomatic->getGlobals($currentTemplate, craft()->language);	        
        }
	    return $result;
    }

/* --------------------------------------------------------------------------------
    Return our twig filters
-------------------------------------------------------------------------------- */

    public function getFilters()
    {
        return array(
            'renderJSONLD' => new \Twig_Filter_Method($this, 'renderJSONLD'),
            'extractKeywords' => new \Twig_Filter_Method($this, 'extractKeywords'),
            'extractSummary' => new \Twig_Filter_Method($this, 'extractSummary'),
            'truncateStringOnWord' => new \Twig_Filter_Method($this, 'truncateStringOnWord'),
            'encodeEmailAddress' => new \Twig_Filter_Method($this, 'encodeEmailAddress'),
        );
    }

/* --------------------------------------------------------------------------------
    Return our twig functions
-------------------------------------------------------------------------------- */

    public function getFunctions()
    {
        return array(
            'renderJSONLD' => new \Twig_Function_Method($this, 'renderJSONLD'),
            'extractKeywords' => new \Twig_Function_Method($this, 'extractKeywords'),
            'extractSummary' => new \Twig_Function_Method($this, 'extractSummary'),
            'truncateStringOnWord' => new \Twig_Function_Method($this, 'truncateStringOnWord'),
            'encodeEmailAddress' => new \Twig_Function_Method($this, 'encodeEmailAddress'),
        );
    }

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
    Get the current template path 
-------------------------------------------------------------------------------- */

    private function _get_current_template_path()
    {
        $currentTemplate = craft()->templates->getRenderingTemplate();
        $templatesPath = craft()->path->templatesPath;
        
        $path_parts = pathinfo($currentTemplate);
        
        $result = $path_parts['dirname'] . "/" . $path_parts['filename'];

        if (substr($result, 0, strlen($templatesPath)) == $templatesPath) {
            $result = substr($result, strlen($templatesPath));
        }
        
        return $result;
    }
    
} /* -- class SeomaticTwigExtension */
