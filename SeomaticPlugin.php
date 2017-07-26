<?php
namespace Craft;

class SeomaticPlugin extends BasePlugin
{
    public function getName()
    {
        $pluginNameOverride = $this->getSettings()->getAttribute('pluginNameOverride');
        return empty($pluginNameOverride) ? Craft::t('SEOmatic') : $pluginNameOverride;
    }

    public function getDescription()
    {
        return 'A turnkey SEO implementation for Craft CMS that is comprehensive, powerful, and flexible.';
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/nystudio107/seomatic/wiki';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/nystudio107/seomatic/master/releases.json';
    }

    public function getVersion()
    {
        return '1.1.49';
    }

    public function getSchemaVersion()
    {
        return '1.1.25';
    }

    public function getDeveloper()
    {
        return 'nystudio107';
    }

    public function getDeveloperUrl()
    {
        return 'https://nystudio107.com';
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
            if ((craft()->request->isSiteRequest()) && (isset($context['seomaticMeta'])))
            {
                $locale = craft()->language;
                $seomaticMeta = $context['seomaticMeta'];
                $seomaticSiteMeta = $context['seomaticSiteMeta'];
                $seomaticIdentity = $context['seomaticIdentity'];
                $seomaticSocial = $context['seomaticSocial'];
                $seomaticCreator = $context['seomaticCreator'];
                $seomaticHelper = $context['seomaticHelper'];
                $dataLayer = null;
                if (!empty($context['dataLayer'])) {
                    $dataLayer = $context['dataLayer'];
                }

/* -- We want to pass an up-to-date variable context to the template, so pass everything on in */

                $result="";
                $metaVars = array(
                    'seomaticMeta' => $seomaticMeta,
                    'seomaticSiteMeta' => $seomaticSiteMeta,
                    'seomaticIdentity' => $seomaticIdentity,
                    'seomaticSocial' => $seomaticSocial,
                    'seomaticCreator' => $seomaticCreator,
                    'seomaticHelper' => $seomaticHelper,
                    'dataLayer' => $dataLayer,
                );

/* -- Main Entity of Page info, which is optional */

                if (isset($context['seomaticMainEntityOfPage']))
                    $metaVars['seomaticMainEntityOfPage'] = $context['seomaticMainEntityOfPage'];

/* -- Render the seomaticMeta, this is where the magic happens */

                $seomaticTemplatePath = '';
                if (isset($context['seomaticTemplatePath']))
                    $seomaticTemplatePath = $context['seomaticTemplatePath'];
                $result = craft()->seomatic->renderSiteMeta($seomaticTemplatePath, $metaVars, $locale);
                return $result;
            }
        });
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.seomatic.twigextensions.SeomaticTwigExtension');

        return new SeomaticTwigExtension();
    }

    public function registerSiteRoutes()
    {
        return array(
            'humans.txt'                                            => array('action' => 'seomatic/renderHumans'),
            'robots.txt'                                            => array('action' => 'seomatic/renderRobots'),
        );
    }

    public function registerCpRoutes()
    {
        return array(
            'seomatic/site'                                         => array('action' => 'seomatic/editSiteMeta'),
            'seomatic/site/(?P<locale>[-\w\.*]+)'                   => array('action' => 'seomatic/editSiteMeta'),
            'seomatic/identity'                                     => array('action' => 'seomatic/editIdentity'),
            'seomatic/identity/(?P<locale>[-\w\.*]+)'               => array('action' => 'seomatic/editIdentity'),
            'seomatic/social'                                       => array('action' => 'seomatic/editSocial'),
            'seomatic/social/(?P<locale>[-\w\.*]+)'                 => array('action' => 'seomatic/editSocial'),
            'seomatic/creator'                                      => array('action' => 'seomatic/editCreator'),
            'seomatic/creator/(?P<locale>[-\w\.*]+)'                => array('action' => 'seomatic/editCreator'),
            'seomatic/meta/new'                                     => array('action' => 'seomatic/editMeta'),
            'seomatic/meta/new/(?P<locale>[-\w\.*]+)'               => array('action' => 'seomatic/editMeta'),
            'seomatic/meta/(?P<metaId>\d+)'                         => array('action' => 'seomatic/editMeta'),
            'seomatic/meta/(?P<metaId>\d+)/(?P<locale>[-\w\.*]+)'   => array('action' => 'seomatic/editMeta'),
            'seomatic/settings'                                     => array('action' => 'seomatic/editSettings'),
        );
    }

    /**
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'pluginNameOverride'  => AttributeType::String
        );
    }

    public function onAfterInstall()
    {

/* -- Show our "Welcome to SEOmatic" message */

       if (!craft()->isConsole())
            craft()->request->redirect(UrlHelper::getCpUrl('seomatic/welcome'));

    }

} /* -- class SeomaticPlugin */
