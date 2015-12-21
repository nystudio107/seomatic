<?php
namespace Craft;

class SeomaticPlugin extends BasePlugin
{
    public function getName()
    {
         return Craft::t('SEOmatic');
    }

    public function getDescription()
    {
        return 'A turnkey SEO implementation for Craft CMS that is comprehensive, powerful, and flexible.';
    }
    
    public function getDocumentationUrl()
    {
        return 'https://github.com/khalwat/seomatic/blob/master/README.md';
    }
    
    public function getReleaseFeedUrl()
    {
        return 'https://github.com/khalwat/seomatic/blob/master/releases.json';
    }
    
    public function getVersion()
    {
        return '1.0.3';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'nystudio107';
    }

    public function getDeveloperUrl()
    {
        return 'http://nystudio107.com';
    }

    public function hasCpSection()
    {
        return true;
    }

    public function init()
    {
        require_once __DIR__ . '/vendor/autoload.php';

        craft()->templates->hook('seomaticRender', function(&$context)
        {

			$locale = craft()->language;
            $seomaticMeta = $context['seomaticMeta'];
            $seomaticSiteMeta = $context['seomaticSiteMeta'];
            $seomaticIdentity = $context['seomaticIdentity'];
            $seomaticSocial = $context['seomaticSocial'];
            $seomaticCreator = $context['seomaticCreator'];

/* -- We want to pass an up-to-date variable context to the template, so pass everything on in */

            $result="";
            $metaVars = array(
                'seomaticMeta' => $seomaticMeta,
                'seomaticSiteMeta' => $seomaticSiteMeta,
                'seomaticIdentity' => $seomaticIdentity,
                'seomaticSocial' => $seomaticSocial,
                'seomaticCreator' => $seomaticCreator,
                
            );
            
            craft()->seomatic->sanitizeMetaVars($metaVars);
            
            $seomaticTemplatePath = '';
            if (isset($context['seomaticTemplatePath']))
                $seomaticTemplatePath = $context['seomaticTemplatePath'];
            $result = craft()->seomatic->renderSiteMeta($seomaticTemplatePath, $metaVars, $locale);
            return $result;
        });
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.seomatic.twigextensions.SeomaticTwigExtension');

        return new SeomaticTwigExtension();
    }

    public function registerCpRoutes()
    {
        return array(
            'seomatic/site'             							=> array('action' => 'seomatic/editSiteMeta'),
            'seomatic/site/(?P<locale>[-\w\.*]+)'   				=> array('action' => 'seomatic/editSiteMeta'),
            'seomatic/identity'             						=> array('action' => 'seomatic/editIdentity'),
            'seomatic/identity/(?P<locale>[-\w\.*]+)'             	=> array('action' => 'seomatic/editIdentity'),
            'seomatic/social'             							=> array('action' => 'seomatic/editSocial'),
            'seomatic/social/(?P<locale>[-\w\.*]+)'             	=> array('action' => 'seomatic/editSocial'),
            'seomatic/creator'             							=> array('action' => 'seomatic/editCreator'),
            'seomatic/creator/(?P<locale>[-\w\.*]+)'             	=> array('action' => 'seomatic/editCreator'),
            'seomatic/new'                  						=> array('action' => 'seomatic/editMeta'),
            'seomatic/meta/(?P<metaId>\d+)' 						=> array('action' => 'seomatic/editMeta'),
            'seomatic/meta/(?P<metaId>\d+)/(?P<locale>[-\w\.*]+)' 	=> array('action' => 'seomatic/editMeta'),
        );
    }

    public function onAfterInstall()
    {

/* -- Show our "Welcome to SEOmatic" message */

    craft()->request->redirect(UrlHelper::getCpUrl('seomatic/welcome'));

    }
    
} /* -- class SeomaticPlugin */
