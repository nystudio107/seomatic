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
        $currentTemplate = $this->_get_current_template_path();
        $result = craft()->seomatic->getGlobals($currentTemplate, craft()->language);
        
        return $result;
    }

/* --------------------------------------------------------------------------------
    Return our twig filters
-------------------------------------------------------------------------------- */

    public function getFilters()
    {
        return array(
            'extractKeywords' => new \Twig_Filter_Method($this, 'extractKeywords'),
            'extractSummary' => new \Twig_Filter_Method($this, 'extractSummary'),
        );
    }

/* --------------------------------------------------------------------------------
    Return our twig functions
-------------------------------------------------------------------------------- */

    public function getFunctions()
    {
        return array(
            'extractKeywords' => new \Twig_Function_Method($this, 'extractKeywords'),
            'extractSummary' => new \Twig_Function_Method($this, 'extractSummary'),
        );
    }

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
