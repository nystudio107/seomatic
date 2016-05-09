<?php
namespace Craft;

use \crodas\TextRank\Config;
use \crodas\TextRank\TextRank;
use \crodas\TextRank\Summary;
use \crodas\TextRank\Stopword;

class SeomaticService extends BaseApplicationComponent
{

    protected $entryMeta = null;
    protected $entrySeoCommerceVariants = null;
    protected $cachedSettings = array();
    protected $cachedSiteMeta = array();
    protected $cachedIdentity = array();
    protected $cachedIdentityJSONLD = array();
    protected $cachedSocial = array();
    protected $cachedCreator = array();
    protected $cachedCreatorJSONLD = array();
    protected $cachedProductJSONLD = array();
    protected $cachedWebSiteJSONLD = array();
    protected $renderedMetaVars = null;

/* --------------------------------------------------------------------------------
    Render the all of the SEO Meta, caching it if possible
-------------------------------------------------------------------------------- */

    public function renderSiteMeta($templatePath="", $metaVars=null, $locale)
    {

        $this->renderedMetaVars = $metaVars;

/* -- Cache the results for speediness; 1 query to rule them all */

        $shouldCache = ($metaVars != null);
        if (craft()->config->get('devMode'))
            $shouldCache = false;
        if ($shouldCache)
        {
            $cacheKey = 'seomatic_metacache_' . $this->getMetaHashStr($templatePath, $metaVars);
            $cache = craft()->cache->get($cacheKey);
            if ($cache)
                return $cache;
        }

/* -- If Minify is installed, minify all the things */

        if (craft()->plugins->getPlugin('Minify'))
            $htmlText = craft()->minify->htmlMin($this->render($templatePath, $metaVars));
        else
            $htmlText = $this->render($templatePath, $metaVars);
        if ($shouldCache)
            craft()->cache->set($cacheKey, $htmlText, null);

        return $htmlText;
    } /* -- renderSiteMeta */

/* --------------------------------------------------------------------------------
    Render the all of the SEO Meta for a "headless" instance of Craft
        $templatePath - the template to use to render the meta, "" for the default
        $entry - the Entry ElementType for this render, null otherwise
        $forTemplate - the Craft template path for this request, e.g. "blog/index"
        $locale - the locale for this render
-------------------------------------------------------------------------------- */
    public function headlessRenderSiteMeta($templatePath="", $entry = null, $forTemplate="", $locale)
    {

    $renderedHTML = "";
    if (!$locale)
        $locale = craft()->language;

/* -- If there is an entry associated with this meta render that has an SEOmatic FieldType in it, make sure it is included */

    if ($entry)
    {
       $entryMeta = craft()->seomatic->getMetaFromElement($entry);
       if ($entryMeta)
            craft()->seomatic->setEntryMeta($entryMeta, "");
    }

/* -- Get the SEOmatic globals for the current template / entry / global meta context */

    $metaVars = craft()->seomatic->getGlobals($forTemplate, $locale);

/* -- Call SEOmatic to render the actual meta for us */

    $renderedHTML = craft()->seomatic->renderSiteMeta($forTemplate, $metaVars, $locale);

    return $renderedHTML;
    } /* -- headlessRenderSiteMeta */

/* --------------------------------------------------------------------------------
    Render the SEOmatic template
-------------------------------------------------------------------------------- */

    public function render($templatePath="", $metaVars=null, $isPreview=false)
    {

        if ($templatePath)
            {
            $htmlText = craft()->templates->render($templatePath, $metaVars);
            }
        else
            {
            $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

/* -- Render the core template */

            $templateName = '_seo_meta';
            if ($isPreview)
                $templateName = $templateName . 'Preview';
            if ($metaVars)
                {
                $this->sanitizeMetaVars($metaVars);
                $htmlText = craft()->templates->render($templateName, $metaVars);
                }
            else
                $htmlText = craft()->templates->render($templateName);

            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);
            }
        return $htmlText;
    } /* -- render */

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

    public function renderJSONLD($object=array(), $isPreview=false)
    {
        $vars = array("object" => $object);
        $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'seomatic/templates';
        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

/* -- Render the core template */

        $templateName = 'json-ld/_json-ld';
        if (craft()->plugins->getPlugin('Minify') && !$isPreview)
            $htmlText = craft()->minify->jsMin($htmlText = craft()->templates->render($templateName, $vars));
        else
            $htmlText = craft()->templates->render($templateName, $vars);

        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);

        return $htmlText;
    } /* -- renderJSONLD */

/* --------------------------------------------------------------------------------
    Render the SEOmatic display preview template
-------------------------------------------------------------------------------- */

    public function renderDisplayPreview($templateName="", $metaVars)
    {
        $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'seomatic/templates';
        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

/* -- Render the SEOmatic display preview template */

        $this->sanitizeMetaVars($metaVars);
        $htmlText = craft()->templates->render($templateName, $metaVars);

        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);

        return $htmlText;
    } /* -- renderDisplayPreview */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Identity template
-------------------------------------------------------------------------------- */

    public function renderIdentity($metaVars, $locale, $isPreview=false)
    {
        $this->sanitizeMetaVars($metaVars);
        $htmlText = $this->renderJSONLD($metaVars['seomaticIdentity'], $isPreview);
        return $htmlText;
    } /* -- renderIdentity */

/* --------------------------------------------------------------------------------
    Render the SEOmatic WebSite template
-------------------------------------------------------------------------------- */

    public function renderWebsite($metaVars, $locale, $isPreview=false)
    {
        $this->sanitizeMetaVars($metaVars);
        $webSite = $this->getWebSiteJSONLD($metaVars, $locale);
        $htmlText = $this->renderJSONLD($webSite, $isPreview);
        return $htmlText;
    } /* -- renderWebsite */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Product template
-------------------------------------------------------------------------------- */

    public function renderProduct($metaVars, $locale, $isPreview=false)
    {
        $htmlText = "";

        if (isset($metaVars['seomaticProduct']))
        {
            $this->sanitizeMetaVars($metaVars);
            $htmlText = $this->renderJSONLD($metaVars['seomaticProduct'], $isPreview);
        }
        return $htmlText;
    } /* -- renderWebsite */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Place template
-------------------------------------------------------------------------------- */

    public function renderPlace($metaVars, $locale, $isPreview=false)
    {
        $htmlText = "";
        if (($metaVars['seomaticIdentity']['type'] != "Person") && (isset($metaVars['seomaticIdentity']['location'])))
        {
            $this->sanitizeMetaVars($metaVars);
            $place = $metaVars['seomaticIdentity']['location'];
            if (array_keys($place) !== range(0, count($place) - 1))
            {
                $htmlText = $this->renderJSONLD($place, $isPreview);
            }
            else
            {
                foreach($place as $places)
                {
                    $htmlText .= $this->renderJSONLD($places, $isPreview);
                }
            }
        }
        return $htmlText;
    } /* -- renderPlace */

/* --------------------------------------------------------------------------------
    Render the Google Analytics <script> tags
-------------------------------------------------------------------------------- */

    public function renderGoogleAnalytics($metaVars, $locale, $isPreview=false)
    {
        $htmlText = "";
        $shouldRenderGA = craft()->config->get("renderGoogleAnalyticsScript", "seomatic");
        if (($shouldRenderGA) || ($isPreview))
        {
            $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

    /* -- Render the core template */

            $templateName = '_googleAnalytics';
            if (craft()->plugins->getPlugin('Minify') && !$isPreview)
                $htmlText = craft()->minify->jsMin($htmlText = craft()->templates->render($templateName, $metaVars));
            else
                $htmlText = craft()->templates->render($templateName, $metaVars);

            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);
        }
        return $htmlText;
    } /* -- renderGoogleAnalytics */

/* --------------------------------------------------------------------------------
    Render the SEOmatic globals
-------------------------------------------------------------------------------- */

    public function renderGlobals($metaVars, $forTemplate="")
    {
        $htmlText = "";

        $this->sanitizeMetaVars($metaVars);
        $htmlText = $this->_print_twig_array($metaVars, 0);
        return $htmlText;
    } /* -- renderGlobals */

/* --------------------------------------------------------------------------------
    Render the humans.txt template
-------------------------------------------------------------------------------- */

    public function renderHumans($isPreview=false)
    {
        $templatePath = '';
        $locale = '';
        if (!$locale)
            $locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);

        if ($templatePath)
        {
            $htmlText = craft()->templates->render($templatePath);
        }
        else
        {
            $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

/* -- Render the core template */

            $templateName = '_humans';
            if ($isPreview)
                $templateName = $templateName . 'Preview';
            $htmlText = craft()->templates->render($templateName, $metaVars);

            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);
        }

        return $htmlText;
    } /* -- renderHumans */

/* --------------------------------------------------------------------------------
    Render the humans.txt user-defined template
-------------------------------------------------------------------------------- */

    public function renderHumansTemplate()
    {
        $templatePath = '';
        $locale = '';
        if (!$locale)
            $locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $creator = craft()->seomatic->getCreator($locale);

/* -- Render the user-defined Humans.txt template */

        $template = $creator['genericCreatorHumansTxt'];
        $htmlText = craft()->templates->renderString($template, $metaVars);

        return $htmlText;
    } /* -- renderHumansTemplate */

/* --------------------------------------------------------------------------------
    Render the robots.txt template
-------------------------------------------------------------------------------- */

    public function renderRobots($isPreview=false)
    {
        $templatePath = '';
        $locale = '';
        if (!$locale)
            $locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);

        if ($templatePath)
        {
            $htmlText = craft()->templates->render($templatePath);
        }
        else
        {
            $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

/* -- Render the core template */

            $templateName = '_robots';
            if ($isPreview)
                $templateName = $templateName . 'Preview';
            $htmlText = craft()->templates->render($templateName, $metaVars);

            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);
        }

        return $htmlText;
    } /* -- renderRobots */

/* --------------------------------------------------------------------------------
    Render the robots.txt user-defined template
-------------------------------------------------------------------------------- */

    public function renderRobotsTemplate()
    {
        $templatePath = '';
        $locale = '';
        if (!$locale)
            $locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);
        $siteMeta = craft()->seomatic->getSiteMeta($locale);

/* -- Render the user-defined robots.txt template */

        $template = $siteMeta['siteRobotsTxt'];
        $htmlText = craft()->templates->renderString($template, $metaVars);

        return $htmlText;
    } /* -- renderRobotsTemplate */

/* --------------------------------------------------------------------------------
    Try to extract an seomaticMeta field from an element
-------------------------------------------------------------------------------- */

    public function getMetaFromElement($element)
    {
/* -- See if there is an 'entry' automagically put into this template, and if it contains an Seomatic_Meta */

        $entryMeta = null;
        $entryMetaUrl = "";
        if (isset($element) && $element)
        {
            $elemType = $element->getElementType();
            if ($elemType == ElementType::Entry ||
                $elemType == "Commerce_Product" ||
                $elemType == ElementType::Category)
            {
                $attributes = $element->content->attributes;
                foreach ($attributes as $key => $value)
                {
                    if (is_object($value) && property_exists($value, "elementType"))
                    {
                        if ($value->elementType == "Seomatic_FieldMeta")
                        {
                            $entryMeta = $value;
                            $entryMetaUrl = $this->getFullyQualifiedUrl($element->url);

    /* -- If this is a Commerce Product, fill in some additional info */

                            if ($elemType == "Commerce_Product" && craft()->config->get("renderCommerceProductJSONLD", "seomatic"))
                            {
                                $commerceSettings = craft()->commerce_settings->getSettings();
                                $variants = $element->getVariants();
                                $commerceVariants = array();

                                foreach ($variants as $variant)
                                {
                                    $commerceVariant = array(
                                        'seoProductDescription' => $variant->getDescription(),
                                        'seoProductPrice' => number_format($variant->getPrice(), 2),
                                        'seoProductCurrency' => $commerceSettings['defaultCurrency'],
                                        'seoProductSku' => $variant->getSku(),
                                    );
                                    $commerceVariants[] = $commerceVariant;
                                }
                                if (!empty($commerceVariants))
                                    $entryMeta['seoCommerceVariants'] = $commerceVariants;
                            }

    /* -- Swap in any SEOmatic fields that are pulling from other entry fields */

                            switch ($entryMeta['seoTitleSource'])
                            {
                                case 'field':
                                    if (isset($element[$entryMeta['seoTitleSourceField']]))
                                    {
                                        $entryMeta['seoTitle'] = $this->getTextFromEntryField($element[$entryMeta['seoTitleSourceField']]);
                                    }
                                break;

                                case 'custom':
                                    $entryMeta['seoTitle'] = craft()->config->parseEnvironmentString($entryMeta['seoTitle']);
                                    try
                                    {
                                        $entryMeta['seoTitle'] = craft()->templates->renderObjectTemplate($entryMeta['seoTitle'], $element);
                                    }
                                    catch (Exception $e)
                                    {
                                        SeomaticPlugin::log("Template error in the `seoTitle` field.", LogLevel::Info, true);
                                    }
                                break;
                            }

                            switch ($entryMeta['seoDescriptionSource'])
                            {
                                case 'field':
                                    if (isset($element[$entryMeta['seoDescriptionSourceField']]))
                                    {
                                        $entryMeta['seoDescription'] = $this->getTextFromEntryField($element[$entryMeta['seoDescriptionSourceField']]);
                                    }
                                break;

                                case 'custom':
                                    $entryMeta['seoDescription'] = craft()->config->parseEnvironmentString($entryMeta['seoDescription']);
                                    try
                                    {
                                        $entryMeta['seoDescription'] = craft()->templates->renderObjectTemplate($entryMeta['seoDescription'], $element);
                                    }
                                    catch (Exception $e)
                                    {
                                        SeomaticPlugin::log("Template error in the `seoDescription` field.", LogLevel::Info, true);
                                    }
                               break;
                            }

                            switch ($entryMeta['seoKeywordsSource'])
                            {
                                case 'field':
                                    if (isset($element[$entryMeta['seoKeywordsSourceField']]))
                                    {
                                        $entryMeta['seoKeywords'] = $this->getTextFromEntryField($element[$entryMeta['seoKeywordsSourceField']]);
                                    }
                                break;

                                case 'keywords':
                                    if (isset($element[$entryMeta['seoKeywordsSourceField']]))
                                    {
                                        $text = $this->getTextFromEntryField($element[$entryMeta['seoKeywordsSourceField']]);
                                        $entryMeta['seoKeywords'] = $this->extractKeywords($text);
                                    }
                                break;

                                case 'custom':
                                    $entryMeta['seoKeywords'] = craft()->config->parseEnvironmentString($entryMeta['seoKeywords']);
                                    try
                                    {
                                        $entryMeta['seoKeywords'] = craft()->templates->renderObjectTemplate($entryMeta['seoKeywords'], $element);
                                    }
                                    catch (Exception $e)
                                    {
                                        SeomaticPlugin::log("Template error in the `seoDescription` field.", LogLevel::Info, true);
                                    }
                               break;
                            }

                            switch ($entryMeta['seoImageIdSource'])
                            {
                                case 'field':
                                    if (isset($element[$entryMeta['seoImageIdSourceField']]) && $element[$entryMeta['seoImageIdSourceField']]->first())
                                    {
                                        $entryMeta['seoImageId'] = $element[$entryMeta['seoImageIdSourceField']]->first()->id;
                                    }
                                break;
                            }

                        }
                    }
                }
            }
        }
    return $entryMeta;
    } /* -- getMetaFromElement */

/* --------------------------------------------------------------------------------
    Extract text from a generix field, do different things based on the classHandle
-------------------------------------------------------------------------------- */
    public function getTextFromEntryField($srcField)
    {
        $result = "";
        if (isset($srcField->elementType))
        {
            switch ($srcField->elementType->classHandle)
            {
                case ElementType::MatrixBlock:
                    $result= $this->extractTextFromMatrix($srcField);
                    break;

                case ElementType::Tag:
                    $result= $this->extractTextFromTags($srcField);
                    break;

                default:
                    $result = strip_tags($srcField);
                    break;
            }
        }
        else
            $result = strip_tags($srcField);

        return $result;
    }

/* --------------------------------------------------------------------------------
    Extract text from a tags field
-------------------------------------------------------------------------------- */

    public function extractTextFromTags($tags)
    {
        $result = "";
        foreach($tags as $tag)
        {
            $result .= $tag->title . ", ";
        }
        $result = rtrim($result, ", ");
        return $result;
    }

/* --------------------------------------------------------------------------------
    Extract text from a matrix field
-------------------------------------------------------------------------------- */

    public function extractTextFromMatrix($matrixBlocks)
    {
        $result = "";
        foreach ($matrixBlocks as $block)
        {
            $matrixBlockTypeModel = $block->getType();
            $fields = $matrixBlockTypeModel->getFields();

            foreach ($fields as $field)
            {
                if ($field->type == "PlainText"
                    || $field->type == "RichText"
                    || $field->type == "RedactorI)"
                    )
                    {
                        $result .= strip_tags($block[$field->handle]) . " ";
                    }
            }

        }
        return $result;
    } /* -- extractTextFromMatrix */

/* --------------------------------------------------------------------------------
    Set the entry-level meta
-------------------------------------------------------------------------------- */

    public function setEntryMeta($entryMeta, $entryMetaUrl)
    {

        $meta = null;

/* -- If $entryMeta was passed in, merge it with our array */

        if ($entryMeta)
        {
            $meta = array();
            $meta['seoTitle'] = $entryMeta->seoTitle;
            $meta['seoDescription'] = $entryMeta->seoDescription;
            $meta['seoKeywords'] = $entryMeta->seoKeywords;
            if (isset($entryMeta->seoImageId[0]))
                $meta['seoImageId'] = $entryMeta->seoImageId[0];
            else
                $meta['seoImageId'] = null;
            $meta['canonicalUrl'] =  $this->getFullyQualifiedUrl($entryMetaUrl);

            $meta['twitterCardType'] = $entryMeta->twitterCardType;
            if (!$meta['twitterCardType'])
                $meta['twitterCardType'] = 'summary';
            $meta['openGraphType'] = $entryMeta->openGraphType;
            if (!$meta['openGraphType'])
                $meta['openGraphType'] = 'website';
            if (isset($entryMeta->robots))
                $meta['robots'] = $entryMeta->robots;
            else
                $meta['robots'] = '';

/* -- Swap in the seoImageId for the actual asset */

            if (isset($entryMeta['seoImageId']))
            {
                $image = craft()->assets->getFileById($entryMeta['seoImageId']);
                if ($image)
                    $meta['seoImage'] = $this->getFullyQualifiedUrl($image->url);
                else
                    $meta['seoImage'] = '';
                unset($meta['seoImageId']);
            }
            else
                $meta['seoImage'] = '';

/* -- For Craft Commerce Products */

            if (isset($entryMeta->seoCommerceVariants) && !empty($entryMeta->seoCommerceVariants))
            {
                $this->entrySeoCommerceVariants = $entryMeta->seoCommerceVariants;
            }
            $meta = array_filter($meta);
        }
        $this->entryMeta = $meta;
    } /* -- setEntryMeta */

/* --------------------------------------------------------------------------------
    Set the Twitter Cards and Open Graph arrays for the meta
-------------------------------------------------------------------------------- */

    public function setSocialForMeta(&$meta, $siteMeta, $social, $helper, $identity, $locale)
    {

        if ($meta)
        {

/* -- Set up the title prefix and suffix */

        $titlePrefix = "";
/* -- We now do this in sanitizeMetaVars() so this can be changed in Twig just like the seoTitle
        if ($siteMeta['siteSeoTitlePlacement'] == "before")
            $titlePrefix =  $siteMeta['siteSeoName'] . " " . $siteMeta['siteSeoTitleSeparator'] . " ";
*/
        $titleSuffix = "";
/* -- We now do this in sanitizeMetaVars() so this can be changed in Twig just like the seoTitle
        if ($siteMeta['siteSeoTitlePlacement'] == "after")
            $titleSuffix = " " . $siteMeta['siteSeoTitleSeparator'] . " " . $siteMeta['siteSeoName'];
*/

/* -- Add in the Twitter Card settings to the meta */

            if ($social['twitterHandle'])
            {
                $twitterCard = array();
                $twitterCard['card'] = $meta['twitterCardType'];
                $twitterCard['site'] = "@" . ltrim($social['twitterHandle'], '@');
                switch ($twitterCard['card'])
                {
                    case 'summary_large_image':
                        $twitterCard['creator'] = "@" . ltrim($social['twitterHandle'], '@');
                    break;

                    default:
                        $twitterCard['creator'] = "";
                    break;
                }
                $twitterCard['title'] = $titlePrefix . $meta['seoTitle'] . $titleSuffix;
                $twitterCard['description'] = $meta['seoDescription'];
                $twitterCard['image'] = $meta['seoImage'];
                $meta['twitter'] = $twitterCard;
            }

    /* -- Add in the Facebook Open Graph settings to the meta */

            $openGraph = array();
            $openGraph['type'] = $meta['openGraphType'];

/* -- Kludges to keep Facebook happy */

            if ($locale == "en")
                $openGraph['locale'] = 'en_US';
            else
                $openGraph['locale'] = $locale;
            if (strlen($openGraph['locale']) == 2)
                $openGraph['locale'] = $openGraph['locale'] . "_" . strtoupper($openGraph['locale']);

            $openGraph['url'] = $meta['canonicalUrl'];
            $openGraph['title'] = $titlePrefix . $meta['seoTitle'] . $titleSuffix;
            $openGraph['description'] = $meta['seoDescription'];
            $openGraph['image'] = $meta['seoImage'];
            $openGraph['site_name'] = $siteMeta['siteSeoName'];

            $sameAs = array();
            array_push($sameAs, $helper['twitterUrl']);
            array_push($sameAs, $helper['facebookUrl']);
            array_push($sameAs, $helper['googlePlusUrl']);
            array_push($sameAs, $helper['linkedInUrl']);
            array_push($sameAs, $helper['youtubeUrl']);
            array_push($sameAs, $helper['youtubeChannelUrl']);
            array_push($sameAs, $helper['instagramUrl']);
            array_push($sameAs, $helper['pinterestUrl']);
            array_push($sameAs, $helper['githubUrl']);
            $sameAs = array_filter($sameAs);
            $sameAs = array_values($sameAs);
            if (!empty($sameAs))
                $openGraph['see_also'] = $sameAs;

            $meta['og'] = $openGraph;

/* -- Handle Open Graph articles */

            if ($openGraph['type'] == "article")
            {
                $openGraphArticle = array();
                $openGraphArticle['author'] = $helper['facebookUrl'];
                $openGraphArticle['publisher'] = $helper['facebookUrl'];
                $openGraphArticle['tag'] = array_map('trim', explode(',', $meta['seoKeywords']));
                $meta['article'] = $openGraphArticle;
            }
        }
    } /* -- setSocialForMeta*/

/* --------------------------------------------------------------------------------
    Get the seomatic globals
-------------------------------------------------------------------------------- */

    public function getGlobals($forTemplate="", $locale)
    {
        if ($this->renderedMetaVars)
            return $this->renderedMetaVars;
        if (!$locale)
            $locale = craft()->language;

/* -- Load in our globals */

        $meta = $this->getMeta($forTemplate);
        $siteMeta = $this->getSiteMeta($locale);
        $identity = $this->getIdentity($locale);
        $social = $this->getSocial($locale);
        $creator = $this->getCreator($locale);

/* -- Get a full qualified URL for the current request */

        $requestUrl = UrlHelper::stripQueryString(craft()->request->url);
        $meta['canonicalUrl'] = $this->getFullyQualifiedUrl($requestUrl);

/* -- Merge the meta with the global meta */

        $globalMeta['seoTitle'] = $siteMeta['siteSeoTitle'];
        $globalMeta['seoDescription'] = $siteMeta['siteSeoDescription'];
        $globalMeta['seoKeywords'] = $siteMeta['siteSeoKeywords'];
        $globalMeta['seoImage'] = $this->getFullyQualifiedUrl($siteMeta['siteSeoImage']);
        $globalMeta['twitterCardType'] = $siteMeta['siteTwitterCardType'];
        $globalMeta['openGraphType'] = $siteMeta['siteOpenGraphType'];
        $globalMeta['robots'] = $siteMeta['siteRobots'];
        $meta = array_merge($globalMeta, $meta);

/* -- Merge with the entry meta, if any */

        if ($this->entryMeta)
            $meta = array_merge($meta, $this->entryMeta);

/* -- Add the helper vars */

        $helper = array();
        $this->addSocialHelpers($helper, $social, $identity);
        $this->addIdentityHelpers($helper, $identity);
        $this->addCreatorHelpers($helper, $creator);

        $this->setSocialForMeta($meta, $siteMeta, $social, $helper, $identity, $locale);

/* -- Get rid of variables we don't want to expose */

        unset($siteMeta['siteSeoImageId']);
        unset($siteMeta['siteTwitterCardType']);
        unset($siteMeta['siteOpenGraphType']);
        unset($siteMeta['siteRobotsTxt']);

        unset($meta['twitterCardType']);
        unset($meta['openGraphType']);

/* -- Set some useful runtime variables, too */

        $runtimeVars = array(
            'seomaticTemplatePath' => '',
        );

/* -- Return everything is an array of arrays */

        $result = array('seomaticMeta' => $meta,
                        'seomaticHelper' => $helper,
                        'seomaticSiteMeta' => $siteMeta,
                        'seomaticSocial' => $social,
                        'seomaticIdentity' => $identity,
                        'seomaticCreator' => $creator,
                        );

/* -- Swap in our JSON-LD objects */

        $result['seomaticIdentity'] = $this->getIdentityJSONLD($result['seomaticIdentity'], $helper, $locale);
        $result['seomaticCreator'] = $this->getCreatorJSONLD($result['seomaticCreator'], $helper, $locale);
        if ($this->entryMeta && isset($this->entrySeoCommerceVariants) && !empty($this->entrySeoCommerceVariants))
            $result['seomaticProduct'] = $this->getProductJSONLD($result, $locale);

/* -- Return our global variables */

        $result = array_merge($result, $runtimeVars);

        return $result;
    } /* -- getGlobals */

/* --------------------------------------------------------------------------------
    Get the Settings record
    We do it this way so that there is only one DB query to load in all of the
    SEOmatic settings.  Originally there were 4 separate models & 4 DB queries.
-------------------------------------------------------------------------------- */

    public function getSettings($locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedSettings[$locale]))
            {
            return $this->cachedSettings[$locale];
            }

/* -- There's only one Seomatic_SettingsRecord per locale */

        $settings = Seomatic_SettingsRecord::model()->findByAttributes(array(
            'locale' => $locale,
            ));
        if (!$settings)
        {
            $this->saveDefaultSettings($locale);
            $settings = Seomatic_SettingsRecord::model()->findByAttributes(array(
            'locale' => $locale,
            ));
        }

        $result = $settings->attributes;

/* -- If our Humans.txt field is empty, fill it with the default template */

        if ($result['genericCreatorHumansTxt'] == "")
            $result['genericCreatorHumansTxt'] = $settings->getDefaultHumans();

/* -- If our robots.txt field is empty, fill it with the default template */

        if ($result['siteRobotsTxt'] == "")
            $result['siteRobotsTxt'] = $settings->getDefaultRobots();

/* -- If our siteSeoTitleSeparator &  empty, fill it with the default template */

        if ($result['siteSeoTitleSeparator'] == "")
            $result['siteSeoTitleSeparator'] = '|';
        if ($result['siteSeoTitlePlacement'] == "")
            $result['siteSeoTitlePlacement'] = 'after';

/* -- If this Craft install is localized, and they are asking for a locale other than the main one,
        merge this local settings with their base language */

        if (craft()->isLocalized())
        {
            $baseLocales = craft()->i18n->getSiteLocales();
            $baseLocale = $baseLocales[0]->id;
            if ($baseLocale != $locale)
            {
        /* -- Cache it in our class; no need to fetch it more than once */

                if (isset($this->cachedSettings[$baseLocale]))
                {
                    $baseResult = $this->cachedSettings[$baseLocale];
                }
                else
                {
        /* -- There's only one Seomatic_SettingsRecord per locale */

                    $baseSettings = Seomatic_SettingsRecord::model()->findByAttributes(array(
                        'locale' => $baseLocale,
                        ));
                    if (!$baseSettings)
                    {
                        $this->saveDefaultSettings($baseLocale);
                        $baseSettings = Seomatic_SettingsRecord::model()->findByAttributes(array(
                        'locale' => $baseLocale,
                        ));
                    }
                    $baseResult = $baseSettings->attributes;
                }
                $result = array_filter($result);
                $result = array_merge($baseResult, $result);
            }
        }

/* -- Get rid of properties we don't care about */

        if ($result)
        {
            unset($result['id']);
            unset($result['dateCreated']);
            unset($result['dateUpdated']);
            unset($result['uid']);
        }

        $this->cachedSettings[$locale] = $result;

        return $result;
    } /* -- getSettings */

/* --------------------------------------------------------------------------------
    Save the default Settings record
-------------------------------------------------------------------------------- */

    public function saveDefaultSettings($locale)
    {
        $model = new Seomatic_SettingsModel();
        $model->locale = $locale;

/* -- Append the locale.id if this isn't the main site language */

        if ($locale != craft()->language)
        {
            $suffix = " (" . $locale . ")";
            $model->siteSeoName .= $suffix;
            $model->siteSeoTitle .= $suffix;
            $model->siteSeoDescription .= $suffix;
            $model->siteSeoKeywords .= $suffix;

        }
        $record = new Seomatic_SettingsRecord();
        $record->setAttributes($model->getAttributes(), false);

        if ($record->save())
        {
            // update id on model (for new records)
            $model->setAttribute('id', $record->getAttribute('id'));

            return true;
        }

        else
        {
            $model->addErrors($record->getErrors());

            return false;
        }
    } /* -- saveDefaultSettings */

/* --------------------------------------------------------------------------------
    Get the siteMeta record
    We do it this way so that there is only one DB query to load in all of the
    SEOmatic settings.  Originally there were 4 separate models & 4 DB queries.
-------------------------------------------------------------------------------- */

    public function getSiteMeta($locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedSiteMeta[$locale]))
            return $this->cachedSiteMeta[$locale];

        $settings = $this->getSettings($locale);
        $siteMeta = array();

        $siteMeta['locale'] = $settings['locale'];

        $siteMeta['siteSeoName'] = $settings['siteSeoName'];
        $siteMeta['siteSeoTitle'] = $settings['siteSeoTitle'];
        $siteMeta['siteSeoTitleSeparator'] = $settings['siteSeoTitleSeparator'];
        $siteMeta['siteSeoTitlePlacement'] = $settings['siteSeoTitlePlacement'];
        $siteMeta['siteSeoDescription'] = $settings['siteSeoDescription'];
        $siteMeta['siteSeoKeywords'] = $settings['siteSeoKeywords'];
        $siteMeta['siteSeoImageId'] = $settings['siteSeoImageId'];

        if (isset($settings['siteRobots']))
            $siteMeta['siteRobots'] = $settings['siteRobots'];
        else
            $siteMeta['siteRobots'] = '';

/* -- Handle the organization contact points */

        $siteMeta['siteLinksSearchTargets'] = $settings['siteLinksSearchTargets'];
        $searchTargets = array();
        if (isset($siteMeta['siteLinksSearchTargets']) && is_array($siteMeta['siteLinksSearchTargets']))
        {
            foreach ($siteMeta['siteLinksSearchTargets'] as $searchTarget)
            {
                $searchTargets[] = $searchTarget;
            }
        }
        $searchTargets = array_filter($searchTargets);
        $siteMeta['siteLinksSearchTargets'] = $searchTargets;

        $siteMeta['siteLinksQueryInput'] = $settings['siteLinksQueryInput'];

        $siteMeta['siteTwitterCardType'] = $settings['siteTwitterCardType'];
        if (!$siteMeta['siteTwitterCardType'])
            $siteMeta['siteTwitterCardType'] = 'summary';
        $siteMeta['siteOpenGraphType'] = $settings['siteOpenGraphType'];
        if (!$siteMeta['siteOpenGraphType'])
            $siteMeta['siteOpenGraphType'] = 'website';

/* -- Swap in the seoImageId for the actual asset */

        if (isset($siteMeta['siteSeoImageId']))
        {
            $image = craft()->assets->getFileById($siteMeta['siteSeoImageId']);
            if ($image)
                $siteMeta['siteSeoImage'] = $this->getFullyQualifiedUrl($image->url);
            else
                $siteMeta['siteSeoImage'] = '';
        }
        else
           $siteMeta['siteSeoImage'] = '';

        $siteMeta['siteRobotsTxt'] = $settings['siteRobotsTxt'];

        $result = $siteMeta;

        $this->cachedSiteMeta[$locale] = $result;
        return $result;
    } /* -- getSiteMeta */

/* --------------------------------------------------------------------------------
    Get the Identity record
    We do it this way so that there is only one DB query to load in all of the
    SEOmatic settings.  Originally there were 4 separate models & 4 DB queries.
-------------------------------------------------------------------------------- */

    public function getIdentity($locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedIdentity[$locale]))
            return $this->cachedIdentity[$locale];

        $settings = $this->getSettings($locale);
        $identity = array();

        $identity['locale'] = $settings['locale'];

        $identity['googleSiteVerification'] = $settings['googleSiteVerification'];
        $identity['bingSiteVerification'] = $settings['bingSiteVerification'];
        $identity['googleAnalyticsUID'] = $settings['googleAnalyticsUID'];
        $identity['googleAnalyticsSendPageview'] = $settings['googleAnalyticsSendPageview'];
        $identity['googleAnalyticsAdvertising'] = $settings['googleAnalyticsAdvertising'];
        $identity['googleAnalyticsEcommerce'] = $settings['googleAnalyticsEcommerce'];
        $identity['googleAnalyticsEEcommerce'] = $settings['googleAnalyticsEEcommerce'];
        $identity['googleAnalyticsLinkAttribution'] = $settings['googleAnalyticsLinkAttribution'];
        $identity['googleAnalyticsLinker'] = $settings['googleAnalyticsLinker'];
        $identity['siteOwnerType'] = ucfirst($settings['siteOwnerType']);
        $identity['siteOwnerSubType'] = $settings['siteOwnerSubType'];
        $identity['siteOwnerSpecificType'] = $settings['siteOwnerSpecificType'];

/* -- Handle migrating the old way of storing siteOwnerType */

        if (($identity['siteOwnerType'] != "Organization") && ($identity['siteOwnerType'] != "Person"))
        {
            $identity['siteOwnerSubType'] = $identity['siteOwnerType'];
            $identity['siteOwnerType'] = "Organization";
        }

        if ($identity['siteOwnerSubType'] == "Restaurant")
        {
            $identity['siteOwnerSpecificType'] = $identity['siteOwnerSubType'];
            $identity['siteOwnerSubType'] = "LocalBusiness";
        }

        $identity['genericOwnerName'] = $settings['genericOwnerName'];
        $identity['genericOwnerAlternateName'] = $settings['genericOwnerAlternateName'];
        $identity['genericOwnerDescription'] = $settings['genericOwnerDescription'];
        $identity['genericOwnerUrl'] = $this->getFullyQualifiedUrl($settings['genericOwnerUrl']);
        $identity['genericOwnerImageId'] = $settings['genericOwnerImageId'];
        $image = craft()->assets->getFileById($settings['genericOwnerImageId']);
        if ($image)
            $identity['genericOwnerImage'] = $this->getFullyQualifiedUrl($image->url);
        else
            $identity['genericOwnerImage'] = '';
        $identity['genericOwnerTelephone'] = $settings['genericOwnerTelephone'];
        $identity['genericOwnerEmail'] = $settings['genericOwnerEmail'];
        $identity['genericOwnerStreetAddress'] = $settings['genericOwnerStreetAddress'];
        $identity['genericOwnerAddressLocality'] = $settings['genericOwnerAddressLocality'];
        $identity['genericOwnerAddressRegion'] = $settings['genericOwnerAddressRegion'];
        $identity['genericOwnerPostalCode'] = $settings['genericOwnerPostalCode'];
        $identity['genericOwnerAddressCountry'] = $settings['genericOwnerAddressCountry'];
        $identity['genericOwnerGeoLatitude'] = $settings['genericOwnerGeoLatitude'];
        $identity['genericOwnerGeoLongitude'] = $settings['genericOwnerGeoLongitude'];

        $identity['organizationOwnerDuns'] = $settings['organizationOwnerDuns'];
        $identity['organizationOwnerFounder'] = $settings['organizationOwnerFounder'];
        $identity['organizationOwnerFoundingDate'] = $settings['organizationOwnerFoundingDate'];
        $identity['organizationOwnerFoundingLocation'] = $settings['organizationOwnerFoundingLocation'];
        $identity['organizationOwnerContactPoints'] = $settings['organizationOwnerContactPoints'];

/* -- Handle the organization contact points */

        $contactPoints = array();
        if (isset($identity['organizationOwnerContactPoints']) && is_array($identity['organizationOwnerContactPoints']))
        {
            foreach ($identity['organizationOwnerContactPoints'] as $contacts)
            {
                $spec = array(
                    "type" => "ContactPoint",
                    "telephone" => $contacts['telephone'],
                    "contactType" => $contacts['contactType'],
                );
                $contactPoints[] = $spec;
            }
        }
        $contactPoints = array_filter($contactPoints);
        $identity['contactPoint'] = $contactPoints;
        if (count($identity['contactPoint']) < 1)
            unset($identity['contactPoint']);

        $identity['personOwnerGender'] = $settings['personOwnerGender'];
        $identity['personOwnerBirthPlace'] = $settings['personOwnerBirthPlace'];

        $identity['localBusinessOwnerOpeningHours'] = $settings['localBusinessOwnerOpeningHours'];

/* -- Handle the opening hours specification */

        $days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        $openingHours = array();
        if (isset($identity['localBusinessOwnerOpeningHours']) && is_array($identity['localBusinessOwnerOpeningHours']))
        {
            craft()->seomatic->convertTimes($identity['localBusinessOwnerOpeningHours']);
            $index = 0;
            foreach ($identity['localBusinessOwnerOpeningHours'] as $hours)
            {
                $openTime = "";
                $closeTime = "";
               if (isset($hours['open']) && $hours['open'])
                    $openTime = $hours['open']->format('H:i:s', $hours['open']->getTimeZone());
                if (isset($hours['close']) && $hours['close'])
                    $closeTime = $hours['close']->format('H:i:s', $hours['close']->getTimeZone());
                if ($openTime && $closeTime)
                {
                    $spec = array(
                        "type" => "OpeningHoursSpecification",
                        "closes" => $closeTime,
                        "dayOfWeek" => array($days[$index]),
                        "opens" => $openTime,
                    );
                    $openingHours[] = $spec;
                }
                $index++;
            }
        }
        $openingHours = array_filter($openingHours);
        $identity['openingHoursSpecification'] = $openingHours;
        if (count($identity['openingHoursSpecification']) <= 1)
            unset($identity['openingHoursSpecification']);

        $identity['corporationOwnerTickerSymbol'] = $settings['corporationOwnerTickerSymbol'];

        $identity['restaurantOwnerServesCuisine'] = $settings['restaurantOwnerServesCuisine'];
        $identity['restaurantOwnerMenuUrl'] = $this->getFullyQualifiedUrl($settings['restaurantOwnerMenuUrl']);
        $identity['restaurantOwnerReservationsUrl'] = $this->getFullyQualifiedUrl($settings['restaurantOwnerReservationsUrl']);

        $result = $identity;

        $this->cachedIdentity[$locale] = $result;

        return $result;
    } /* -- getIdentity */

/* --------------------------------------------------------------------------------
    Get the Identity JSON-LD
-------------------------------------------------------------------------------- */

    public function getIdentityJSONLD($identity, $helper, $locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedIdentityJSONLD[$locale]))
            return $this->cachedIdentityJSONLD[$locale];

        $identityJSONLD = array();

/* -- Settings generic to all Identity types */

        $identityJSONLD['type'] = ucfirst($identity['siteOwnerType']);
        if ($identity['siteOwnerSubType'])
            $identityJSONLD['type'] = $identity['siteOwnerSubType'];
        if ($identity['siteOwnerSpecificType'])
            $identityJSONLD['type'] = $identity['siteOwnerSpecificType'];

        $identityJSONLD['name'] = $identity['genericOwnerName'];
        $identityJSONLD['alternateName'] = $identity['genericOwnerAlternateName'];
        $identityJSONLD['description'] = $identity['genericOwnerDescription'];
        $identityJSONLD['url'] = $identity['genericOwnerUrl'];

        $sameAs = array();
        array_push($sameAs, $helper['twitterUrl']);
        array_push($sameAs, $helper['facebookUrl']);
        array_push($sameAs, $helper['googlePlusUrl']);
        array_push($sameAs, $helper['linkedInUrl']);
        array_push($sameAs, $helper['youtubeUrl']);
        array_push($sameAs, $helper['youtubeChannelUrl']);
        array_push($sameAs, $helper['instagramUrl']);
        array_push($sameAs, $helper['pinterestUrl']);
        array_push($sameAs, $helper['githubUrl']);
        $sameAs = array_filter($sameAs);
        $sameAs = array_values($sameAs);
        if (!empty($sameAs))
            $identityJSONLD['sameAs'] = $sameAs;

        if (isset($identity['genericOwnerImage']))
            $identityJSONLD['image'] = $identity['genericOwnerImage'];
        $identityJSONLD['telephone'] = $identity['genericOwnerTelephone'];
        $identityJSONLD['email'] = $identity['genericOwnerEmail'];
        $address = array(
            "type" => "PostalAddress",
            "streetAddress" => $identity['genericOwnerStreetAddress'],
            "addressLocality" => $identity['genericOwnerAddressLocality'],
            "addressRegion" => $identity['genericOwnerAddressRegion'],
            "postalCode" => $identity['genericOwnerPostalCode'],
            "addressCountry" => $identity['genericOwnerAddressCountry']
        );
        $address = array_filter($address);
        $identityJSONLD['address'] = $address;
        if (count($identityJSONLD['address']) == 1)
            unset($identityJSONLD['address']);

/* -- Settings for all person Identity types */

        if ($identity['siteOwnerType'] == "Person")
        {
            $identityJSONLD['gender'] = $identity['personOwnerGender'];
            $identityJSONLD['birthPlace'] = $identity['personOwnerBirthPlace'];
        }

/* -- Settings for all organization Identity types */

        if ($identity['siteOwnerType'] == "Organization")
        {
            if (isset($identity['genericOwnerImage']))
                $identityJSONLD['logo'] = $identity['genericOwnerImage'];
            $geo = array(
                "type" => "GeoCoordinates",
                "latitude" => $identity['genericOwnerGeoLatitude'],
                "longitude" => $identity['genericOwnerGeoLongitude'],
            );
            $geo = array_filter($geo);

            $locImage = "";
            if (isset($identity['genericOwnerImage']))
                $locImage = $identity['genericOwnerImage'];

            $location = array(
                "type" => "Place",
                "name" => $identity['genericOwnerName'],
                "alternateName" => $identity['genericOwnerAlternateName'],
                "description" => $identity['genericOwnerDescription'],
                "hasMap" => $helper['ownerMapUrl'],
                "telephone" =>  $identity['genericOwnerTelephone'],
                "image" =>  $locImage,
                "logo" =>  $locImage,
                "url" =>  $identity['genericOwnerUrl'],
                "sameAs" =>  $sameAs,
                "geo" => $geo,
                "address" => $address,
            );
            $location = array_filter($location);
            $identityJSONLD['location'] = $location;

            if (count($identityJSONLD['location']['geo']) == 1)
                unset($identityJSONLD['location']['geo']);

            if (count($identityJSONLD['location']['address']) == 1)
                unset($identityJSONLD['location']['address']);

            if (count($identityJSONLD['location']) == 1)
                unset($identityJSONLD['location']);

            $identityJSONLD['duns'] = $identity['organizationOwnerDuns'];
            $identityJSONLD['founder'] = $identity['organizationOwnerFounder'];
            $identityJSONLD['foundingDate'] = $identity['organizationOwnerFoundingDate'];
            $identityJSONLD['foundingLocation'] = $identity['organizationOwnerFoundingLocation'];
            if (isset($identity['contactPoint']))
                $identityJSONLD['contactPoint'] = $identity['contactPoint'];
        }

/* -- Settings on a per-Identity sub-type basis */

        switch ($identity['siteOwnerSubType'])
        {
            case 'Airline':
            break;

            case 'Corporation':
                $identityJSONLD['tickerSymbol'] = $identity['corporationOwnerTickerSymbol'];
            break;

            case 'EducationalOrganization':
            break;

            case 'GovernmentOrganization':
            break;

            case 'LocalBusiness':
                if (isset($identity['openingHoursSpecification']))
                {
                    if (isset($identityJSONLD['location']))
                        $identityJSONLD['openingHoursSpecification'] = $identity['openingHoursSpecification'];
                    if (isset($identityJSONLD['location']))
                        $identityJSONLD['location']['openingHoursSpecification'] = $identity['openingHoursSpecification'];
                }
            break;

            case 'NGO':
            break;

            case 'PerformingGroup':
            break;

            case 'SportsOrganization':
            break;

        }

/* -- Settings on a per-Identity specific-type basis */

        switch ($identity['siteOwnerSpecificType'])
        {
            case 'FoodEstablishment':
            case 'Bakery':
            case 'BarOrPub':
            case 'Brewery':
            case 'CafeOrCoffeeShop':
            case 'FastFoodRestaurant':
            case 'IceCreamShop':
            case 'Restaurant':
            case 'Winery':
                $identityJSONLD['servesCuisine'] = $identity['restaurantOwnerServesCuisine'];
                $identityJSONLD['menu'] = $identity['restaurantOwnerMenuUrl'];
                $identityJSONLD['acceptsReservations'] = $identity['restaurantOwnerReservationsUrl'];
            break;
        }

        $result = array_filter($identityJSONLD);

        $this->cachedIdentityJSONLD[$locale] = $result;

        return $result;
    } /* -- getIdentityJSONLD */

/* --------------------------------------------------------------------------------
    Get the social record
    We do it this way so that there is only one DB query to load in all of the
    SEOmatic settings.  Originally there were 4 separate models & 4 DB queries.
-------------------------------------------------------------------------------- */

    public function getSocial($locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedSocial[$locale]))
            return $this->cachedSocial[$locale];

        $settings = $this->getSettings($locale);
        $social = array();

        $social['locale'] = $settings['locale'];

        $social['twitterHandle'] = $settings['twitterHandle'];
        $social['facebookHandle'] = $settings['facebookHandle'];
        $social['facebookProfileId'] = $settings['facebookProfileId'];
        $social['facebookAppId'] = $settings['facebookAppId'];
        $social['linkedInHandle'] = $settings['linkedInHandle'];
        $social['googlePlusHandle'] = $settings['googlePlusHandle'];
        $social['youtubeHandle'] = $settings['youtubeHandle'];
        $social['youtubeChannelHandle'] = $settings['youtubeChannelHandle'];
        $social['instagramHandle'] = $settings['instagramHandle'];
        $social['pinterestHandle'] = $settings['pinterestHandle'];
        $social['githubHandle'] = $settings['githubHandle'];

        $result = $social;

        $this->cachedSocial[$locale] = $result;
        return $result;
    } /* -- getSocial */

/* --------------------------------------------------------------------------------
    Get the Creator record
    We do it this way so that there is only one DB query to load in all of the
    SEOmatic settings.  Originally there were 4 separate models & 4 DB queries.
-------------------------------------------------------------------------------- */

    public function getCreator($locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedCreator[$locale]))
            return $this->cachedCreator[$locale];

        $settings = $this->getSettings($locale);
        $creator = array();

        $creator['locale'] = $settings['locale'];

        $creator['siteCreatorType'] = ucfirst($settings['siteCreatorType']);
        $creator['siteCreatorSubType'] = "";
        $creator['siteCreatorSpecificType'] = "";

/* -- Handle migrating the old way of storing siteCreatorType 
        $creator['siteCreatorSubType'] = $settings['siteCreatorSubType'];
        $creator['siteCreatorSpecificType'] = $settings['siteCreatorSpecificType'];


        if (($creator['siteCreatorType'] != "Organization") && ($creator['siteCreatorType'] != "Person"))
        {
            $creator['siteCreatorSubType'] = $creator['siteCreatorType'];
            $creator['siteCreatorType'] = "Organization";
        }

        if ($creator['siteCreatorSubType'] == "Restaurant")
        {
            $creator['siteCreatorSpecificType'] = $creator['siteCreatorSubType'];
            $creator['siteCreatorSubType'] = "LocalBusiness";
        }
*/
        $creator['genericCreatorName'] = $settings['genericCreatorName'];
        $creator['genericCreatorAlternateName'] = $settings['genericCreatorAlternateName'];
        $creator['genericCreatorDescription'] = $settings['genericCreatorDescription'];
        $creator['genericCreatorUrl'] = $this->getFullyQualifiedUrl($settings['genericCreatorUrl']);
        $creator['genericCreatorImageId'] = $settings['genericCreatorImageId'];
        $image = craft()->assets->getFileById($settings['genericCreatorImageId']);
        if ($image)
            $creator['genericCreatorImage'] = $this->getFullyQualifiedUrl($image->url);
        else
            $creator['genericCreatorImage'] = '';
        $creator['genericCreatorTelephone'] = $settings['genericCreatorTelephone'];
        $creator['genericCreatorEmail'] = $settings['genericCreatorEmail'];
        $creator['genericCreatorStreetAddress'] = $settings['genericCreatorStreetAddress'];
        $creator['genericCreatorAddressLocality'] = $settings['genericCreatorAddressLocality'];
        $creator['genericCreatorAddressRegion'] = $settings['genericCreatorAddressRegion'];
        $creator['genericCreatorPostalCode'] = $settings['genericCreatorPostalCode'];
        $creator['genericCreatorAddressCountry'] = $settings['genericCreatorAddressCountry'];
        $creator['genericCreatorGeoLatitude'] = $settings['genericCreatorGeoLatitude'];
        $creator['genericCreatorGeoLongitude'] = $settings['genericCreatorGeoLongitude'];

        $creator['organizationCreatorDuns'] = $settings['organizationCreatorDuns'];
        $creator['organizationCreatorFounder'] = $settings['organizationCreatorFounder'];
        $creator['organizationCreatorFoundingDate'] = $settings['organizationCreatorFoundingDate'];
        $creator['organizationCreatorFoundingLocation'] = $settings['organizationCreatorFoundingLocation'];
        $creator['organizationCreatorContactPoints'] = $settings['organizationCreatorContactPoints'];

/* -- Handle the organization contact points */

        $contactPoints = array();
        if (isset($creator['organizationCreatorContactPoints']) && is_array($creator['organizationCreatorContactPoints']))
        {
            foreach ($creator['organizationCreatorContactPoints'] as $contacts)
            {
                $spec = array(
                    "type" => "ContactPoint",
                    "telephone" => $contacts['telephone'],
                    "contactType" => $contacts['contactType'],
                );
                $contactPoints[] = $spec;
            }
        }
        $contactPoints = array_filter($contactPoints);
        $creator['contactPoint'] = $contactPoints;
        if (count($creator['contactPoint']) < 1)
            unset($creator['contactPoint']);

        $creator['personCreatorGender'] = $settings['personCreatorGender'];
        $creator['personCreatorBirthPlace'] = $settings['personCreatorBirthPlace'];

        $creator['corporationCreatorTickerSymbol'] = $settings['corporationCreatorTickerSymbol'];

        $identity['restaurantCreatorServesCuisine'] = $settings['restaurantCreatorServesCuisine'];
        $identity['restaurantCreatorMenuUrl'] = $this->getFullyQualifiedUrl($settings['restaurantCreatorMenuUrl']);
        $identity['restaurantCreatorReservationsUrl'] = $this->getFullyQualifiedUrl($settings['restaurantCreatorReservationsUrl']);

        $creator['genericCreatorHumansTxt'] = $settings['genericCreatorHumansTxt'];

        $result = $creator;

        $this->cachedCreator[$locale] = $result;

        return $result;
    } /* -- getCreator */

/* --------------------------------------------------------------------------------
    Get the Creator JSON-LD
-------------------------------------------------------------------------------- */

    public function getCreatorJSONLD($creator, $helper, $locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedCreatorJSONLD[$locale]))
            return $this->cachedCreatorJSONLD[$locale];

        $creatorJSONLD = array();

/* -- Settings generic to all Creator types */

        $creatorJSONLD['type'] = ucfirst($creator['siteCreatorType']);
/*
        if ($creator['siteCreatorSubType'])
            $creatorJSONLD['type'] = $creator['siteCreatorSubType'];
        if ($creator['siteCreatorSpecificType'])
            $creatorJSONLD['type'] = $creator['siteCreatorSpecificType'];
*/
        $creatorJSONLD['name'] = $creator['genericCreatorName'];
        $creatorJSONLD['alternateName'] = $creator['genericCreatorAlternateName'];
        $creatorJSONLD['description'] = $creator['genericCreatorDescription'];
        $creatorJSONLD['url'] = $creator['genericCreatorUrl'];

        if (isset($creator['genericCreatorImage']))
            $creatorJSONLD['image'] = $creator['genericCreatorImage'];
        $creatorJSONLD['telephone'] = $creator['genericCreatorTelephone'];
        $creatorJSONLD['email'] = $creator['genericCreatorEmail'];
        $address = array(
            "type" => "PostalAddress",
            "streetAddress" => $creator['genericCreatorStreetAddress'],
            "addressLocality" => $creator['genericCreatorAddressLocality'],
            "addressRegion" => $creator['genericCreatorAddressRegion'],
            "postalCode" => $creator['genericCreatorPostalCode'],
            "addressCountry" => $creator['genericCreatorAddressCountry']
        );
        $address = array_filter($address);
        $creatorJSONLD['address'] = $address;
        if (count($creatorJSONLD['address']) == 1)
            unset($creatorJSONLD['address']);

/* -- This needs to be an additional fieldtype if we implement it
        if ($creator['genericCreatorTelephone'])
        {
            $contactPoint = array(
                "type" => "ContactPoint",
                "telephone" => $creator['genericCreatorTelephone'],
                "contactType" => "Contact",
            );
            $contactPoint = array_filter($contactPoint);
            $creatorJSONLD['contactPoint'] = array($contactPoint);
        }
*/

/* -- Settings for all person Creator types */

        if ($creator['siteCreatorType'] == "Person")
        {
            $creatorJSONLD['gender'] = $creator['personCreatorGender'];
            $creatorJSONLD['birthPlace'] = $creator['personCreatorBirthPlace'];
        }

/* -- Settings for all organization Creator types */

        if ($creator['siteCreatorType'] == "Organization" || $creator['siteCreatorType'] == "Corporation")
        {
            if (isset($creator['genericCreatorImage']))
                $creatorJSONLD['logo'] = $creator['genericCreatorImage'];
            $geo = array(
                "type" => "GeoCoordinates",
                "latitude" => $creator['genericCreatorGeoLatitude'],
                "longitude" => $creator['genericCreatorGeoLongitude'],
            );
            $geo = array_filter($geo);

            $locImage = "";
            if (isset($identity['genericCreatorImage']))
                $locImage = $identity['genericCreatorImage'];

            $location = array(
                "type" => "Place",
                "name" => $creator['genericCreatorName'],
                "alternateName" => $creator['genericCreatorAlternateName'],
                "description" => $creator['genericCreatorDescription'],
                "hasMap" => $helper['creatorMapUrl'],
                "telephone" =>  $creator['genericCreatorTelephone'],
                "image" =>  $locImage,
                "logo" =>  $locImage,
                "url" =>  $creator['genericCreatorUrl'],
                "geo" => $geo,
                "address" => $address,
            );
            $location = array_filter($location);
            $creatorJSONLD['location'] = $location;

            if (count($creatorJSONLD['location']['geo']) == 1)
                unset($creatorJSONLD['location']['geo']);

            if (count($creatorJSONLD['location']['address']) == 1)
                unset($creatorJSONLD['location']['address']);

            if (count($creatorJSONLD['location']) == 1)
                unset($creatorJSONLD['location']);

            $creatorJSONLD['duns'] = $creator['organizationCreatorDuns'];
            $creatorJSONLD['founder'] = $creator['organizationCreatorFounder'];
            $creatorJSONLD['foundingDate'] = $creator['organizationCreatorFoundingDate'];
            $creatorJSONLD['foundingLocation'] = $creator['organizationCreatorFoundingLocation'];
            if (isset($creator['contactPoint']))
                $creatorJSONLD['contactPoint'] = $creator['contactPoint'];
        }

/* -- Settings on a per-Creator sub-type basis */

        switch ($creator['siteCreatorSubType'])
        {
            case 'Airline':
            break;

            case 'Corporation':
                $creatorJSONLD['tickerSymbol'] = $creator['corporationCreatorTickerSymbol'];
            break;

            case 'EducationalOrganization':
            break;

            case 'GovernmentOrganization':
            break;

            case 'LocalBusiness':
            break;

            case 'NGO':
            break;

            case 'PerformingGroup':
            break;

            case 'SportsOrganization':
            break;

        }

/* -- Settings on a per-Creator specific-type basis */

        switch ($creator['siteCreatorSpecificType'])
        {
            case 'FoodEstablishment':
            case 'Bakery':
            case 'BarOrPub':
            case 'Brewery':
            case 'CafeOrCoffeeShop':
            case 'FastFoodRestaurant':
            case 'IceCreamShop':
            case 'Restaurant':
            case 'Winery':
                $creatorJSONLD['servesCuisine'] = $creator['restaurantCreatorServesCuisine'];
                $creatorJSONLD['menu'] = $creator['restaurantCreatorMenuUrl'];
                $creatorJSONLD['acceptsReservations'] = $creator['restaurantCreatorReservationsUrl'];
            break;
        }

        $result = array_filter($creatorJSONLD);

        $this->cachedCreatorJSONLD[$locale] = $result;

        return $result;
    } /* -- getCreatorJSONLD */

/* --------------------------------------------------------------------------------
    Get the Product JSON-LD
-------------------------------------------------------------------------------- */

    public function getProductJSONLD($metaVars, $locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedProductJSONLD[$locale]))
            return $this->cachedProductJSONLD[$locale];

        $productsArrayJSONLD = array();

        foreach ($this->entrySeoCommerceVariants as $variant)
        {
            $productJSONLD = array();

    /* -- Settings generic to all Creator types */

            $productJSONLD['type'] = "Product";
            $productJSONLD['name'] = $variant['seoProductDescription'];
            $productJSONLD['description'] = $metaVars['seomaticMeta']['seoDescription'];
            $productJSONLD['image'] = $metaVars['seomaticMeta']['seoImage'];
            $productJSONLD['logo'] = $metaVars['seomaticMeta']['seoImage'];
            $productJSONLD['url'] = $metaVars['seomaticMeta']['canonicalUrl'];

            $productJSONLD['sku'] = $variant['seoProductSku'];

            $offer = array(
                "type" => "Offer",
                "url" => $metaVars['seomaticMeta']['canonicalUrl'],
                "price" =>  $variant['seoProductPrice'],
                "priceCurrency" =>  $variant['seoProductCurrency'],
                "offeredBy" =>  $metaVars['seomaticIdentity'],
                "seller" =>  $metaVars['seomaticIdentity'],
            );
            $offer = array_filter($offer);
            $productJSONLD['offers'] = $offer;

            $productsArrayJSONLD[] = array_filter($productJSONLD);
        }

        $this->cachedProductJSONLD[$locale] = $productsArrayJSONLD;

        return $productsArrayJSONLD;
    } /* -- getProductJSONLD */

/* --------------------------------------------------------------------------------
    Get the WebSite JSON-LD
-------------------------------------------------------------------------------- */

    public function getWebSiteJSONLD($metaVars, $locale)
    {

/* -- Cache it in our class; no need to fetch it more than once */

        if (isset($this->cachedWebSiteJSONLD[$locale]))
            return $this->cachedWebSiteJSONLD[$locale];

        $webSiteJSONLD = array();

/* -- Settings generic to all Creator types */

        $webSiteJSONLD['type'] = "WebSite";
        $webSiteJSONLD['name'] = $metaVars['seomaticSiteMeta']['siteSeoName'];
        $webSiteJSONLD['description'] = $metaVars['seomaticSiteMeta']['siteSeoDescription'];
        $webSiteJSONLD['url'] = $this->getFullyQualifiedUrl(craft()->getSiteUrl());
        if (isset($metaVars['seomaticSiteMeta']['siteSeoImage']))
            $webSiteJSONLD['image'] = $metaVars['seomaticSiteMeta']['siteSeoImage'];

        if (!empty($metaVars['seomaticSiteMeta']['siteLinksSearchTargets']) && $metaVars['seomaticSiteMeta']['siteLinksQueryInput'])
        {
            $targets = array();
            foreach ($metaVars['seomaticSiteMeta']['siteLinksSearchTargets'] as $target)
            {
                $targets[] = $target['searchtargets'];
            }
            $potentialAction = array (
                "type" => "SearchAction",
                "target" => $targets,
                "query-input" => "required name=" . $metaVars['seomaticSiteMeta']['siteLinksQueryInput'],
                );
        $webSiteJSONLD['potentialAction'] = $potentialAction;
        }

        $sameAs = array();
        array_push($sameAs, $metaVars['seomaticHelper']['twitterUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['facebookUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['googlePlusUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['linkedInUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['youtubeUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['youtubeChannelUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['instagramUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['pinterestUrl']);
        array_push($sameAs, $metaVars['seomaticHelper']['githubUrl']);
        $sameAs = array_filter($sameAs);
        $sameAs = array_values($sameAs);
        if (!empty($sameAs))
            $webSiteJSONLD['sameAs'] = $sameAs;

        $webSiteJSONLD['copyrightHolder'] = $metaVars['seomaticIdentity'];
        $webSiteJSONLD['author'] = $metaVars['seomaticIdentity'];
        $webSiteJSONLD['creator'] = $metaVars['seomaticCreator'];

        $result = array_filter($webSiteJSONLD);

        $this->cachedWebSiteJSONLD[$locale] = $result;

        return $result;
    } /* -- getWebSiteJSONLD */

/* --------------------------------------------------------------------------------
    Get the meta record
-------------------------------------------------------------------------------- */

    public function getMeta($forTemplate="")
    {
        $result = array();

        if ($forTemplate)
        {
            $forTemplate = craft()->db->quoteValue($forTemplate);
            $whereQuery = '`metaPath` = ' . $forTemplate;
            $metaRecord = Seomatic_MetaRecord::model()->find($whereQuery);
            if ($metaRecord)
            {
                $meta['seoTitle'] = $metaRecord->seoTitle;
                $meta['seoDescription'] = $metaRecord->seoDescription;
                $meta['seoKeywords'] = $metaRecord->seoKeywords;
                if (isset($metaRecord->seoImageId))
                    $meta['seoImageId'] = $metaRecord->seoImageId;
                else
                    $meta['seoImageId'] = null;

                $meta['twitterCardType'] = $metaRecord->twitterCardType;
                if (!$meta['twitterCardType'])
                    $meta['twitterCardType'] = 'summary';
                $meta['openGraphType'] = $metaRecord->openGraphType;
                if (!$meta['openGraphType'])
                    $meta['openGraphType'] = 'website';

                if (isset($metaRecord->robots))
                    $meta['robots'] = $metaRecord->robots;
                else
                    $meta['robots'] = '';

/* -- Swap in the seoImageId for the actual asset */

                if (isset($meta['seoImageId']))
                {
                    $image = craft()->assets->getFileById($meta['seoImageId']);
                    if ($image)
                        $meta['seoImage'] = $this->getFullyQualifiedUrl($image->url);
                    else
                        $meta['seoImage'] = '';
                    unset($meta['seoImageId']);
                }
                else
                    $meta['seoImage'] = '';
                $meta = array_filter($meta);
                $result = array_merge($result, $meta);
            }

        }

        return $result;
    } /* -- getMeta */

/* --------------------------------------------------------------------------------
    Save the meta element & record from the model
-------------------------------------------------------------------------------- */

    public function saveMeta(&$model)
    {
        $isNewMeta = !$model->id;

        if (!$isNewMeta)
        {
            $record = Seomatic_MetaRecord::model()->findById($model->id);
            /*
            $record = Seomatic_SettingsRecord::model()->findByAttributes(array(
                'locale' => $locale,
                'elementId' => $model->elementId,
                ));
            */
            if (!$record)
            {
                throw new Exception(Craft::t('No meta exists with the ID “{id}”', array('id' => $model->id)));
            }
            else
            {

/* -- If the locale of the saved record is different than the locale of our model, create a new record

                if ($record->locale != $model->locale)
                {
                $record = new Seomatic_MetaRecord();
                $model->id = null;
                $isNewMeta = true;
                }
                */
            }
        }
        else
        {
            $record = new Seomatic_MetaRecord();
        }

        $record->setAttributes($model->getAttributes(), false);
        $assetId = (!empty($model->seoImageId) ? $model->seoImageId[0] : null);
        $record->seoImageId = $assetId;

        if (!$record->validate())
        {
            // Copy the record's errors over to the ad model
            $model->addErrors($record->getErrors());

            // Might as well validate the content as well,
            // so we get a complete list of validation errors
            if (!craft()->content->validateContent($model))
            {
                // Copy the content model's errors over to the ad model
                $model->addErrors($model->getContent()->getErrors());
            }

            return false;
        }

        if (!$model->hasErrors())
        {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try
            {
                if (craft()->elements->saveElement($model))
                {
                    if ($isNewMeta)
                    {
                        $record->id = $model->id;
                        $record->elementId = $model->id;
                    }
                    $record->save(false);

                    if ($transaction !== null)
                    {
                        $transaction->commit();
                    }

                    return true;
                }
            }
            catch (\Exception $e)
            {
                if ($transaction !== null)
                {
                    $transaction->rollback();
                }

                throw $e;
            }
        }
    } /* -- saveMeta */

/* --------------------------------------------------------------------------------
    Returns a meta by its ID
-------------------------------------------------------------------------------- */

    public function getMetaById($metaId, $locale=null)
    {
        $result = craft()->elements->getElementById($metaId, 'Seomatic_Meta', $locale);

        return $result;
    } /* -- getMetaById */

/* --------------------------------------------------------------------------------
    Add the social media URLs to 'seomaticHelper'
-------------------------------------------------------------------------------- */

    private function addSocialHelpers(&$helper, $social, $identity)
    {
        if ($social['twitterHandle'])
        {
            ltrim($social['twitterHandle'], '@');
            $helper['twitterUrl'] = "https://twitter.com/" . $social['twitterHandle'];
        }
        else
            $helper['twitterUrl'] = '';

        if ($social['facebookHandle'])
        {
            $helper['facebookUrl'] = "https://www.facebook.com/" . $social['facebookHandle'];
        }
        else
            $helper['facebookUrl'] = '';

        if ($social['googlePlusHandle'])
        {
            ltrim($social['googlePlusHandle'], '+');
            if (is_numeric(substr($social['googlePlusHandle'], 0, 1)))
                $helper['googlePlusUrl'] = "https://plus.google.com/" . $social['googlePlusHandle'];
            else
                $helper['googlePlusUrl'] = "https://plus.google.com/+" . $social['googlePlusHandle'];
        }
        else
            $helper['googlePlusUrl'] = '';

        if ($social['linkedInHandle'])
        {
            if ($identity['siteOwnerType'] == "Person")
                $helper['linkedInUrl'] = "https://www.linkedin.com/in/" . $social['linkedInHandle'];
            else
                $helper['linkedInUrl'] = "https://www.linkedin.com/company/" . $social['linkedInHandle'];
        }
        else
            $helper['linkedInUrl'] = '';

        if ($social['youtubeHandle'])
        {
            $helper['youtubeUrl'] = "https://www.youtube.com/user/" . $social['youtubeHandle'];
        }
        else
            $helper['youtubeUrl'] = '';

        if ($social['youtubeChannelHandle'])
        {
            $helper['youtubeChannelUrl'] = "https://www.youtube.com/c/" . $social['youtubeChannelHandle'];
        }
        else
            $helper['youtubeChannelUrl'] = '';


        if ($social['instagramHandle'])
        {
            $helper['instagramUrl'] = "https://www.instagram.com/" . $social['instagramHandle'];
        }
        else
            $helper['instagramUrl'] = '';

        if ($social['pinterestHandle'])
        {
            $helper['pinterestUrl'] = "https://www.pinterest.com/" . $social['pinterestHandle'];
        }
        else
            $helper['pinterestUrl'] = '';

        if ($social['githubHandle'])
        {
            $helper['githubUrl'] = "https://github.com/" . $social['githubHandle'];
        }
        else
            $helper['githubUrl'] = '';
    } /* -- addSocialHelpers */

/* --------------------------------------------------------------------------------
    Add the Identity helper strings to 'seomaticHelper'
-------------------------------------------------------------------------------- */

    private function addIdentityHelpers(&$helper, $identity)
    {

/* -- Computed identity strings */

        $helper['ownerGoogleSiteVerification'] = $identity['googleSiteVerification'];
        $helper['ownerBingSiteVerification'] = $identity['bingSiteVerification'];
        $helper['ownerGoogleAnalyticsUID'] = $identity['googleAnalyticsUID'];
        $helper['googleAnalyticsSendPageview'] = $identity['googleAnalyticsSendPageview'];
        $helper['googleAnalyticsAdvertising'] = $identity['googleAnalyticsAdvertising'];
        $helper['googleAnalyticsEcommerce'] = $identity['googleAnalyticsEcommerce'];
        $helper['googleAnalyticsEEcommerce'] = $identity['googleAnalyticsEEcommerce'];
        $helper['googleAnalyticsLinkAttribution'] = $identity['googleAnalyticsLinkAttribution'];
        $helper['googleAnalyticsLinker'] = $identity['googleAnalyticsLinker'];
        $now = new DateTime;
        $period = ".";
        $name = $identity['genericOwnerName'];
        if ($name)
        {
            if ($name[strlen($name) -1] == '.')
                $period =" ";
        }

        $helper['ownerCopyrightNotice'] = Craft::t("Copyright") . " &copy;" . $now->year() . " " . $name . $period;

        $helper['ownerAddressString'] = '';
        $helper['ownerAddressHtml'] = '';
        $helper['ownerMapUrl'] = '';
        if ($identity['genericOwnerStreetAddress'] &&
            $identity['genericOwnerAddressLocality'] &&
            $identity['genericOwnerAddressRegion'] &&
            $identity['genericOwnerPostalCode'])
        {
            $helper['ownerAddressString'] = $identity['genericOwnerName'] . ", "
                                        . $identity['genericOwnerStreetAddress'] . ", "
                                        . $identity['genericOwnerAddressLocality'] . ", "
                                        . $identity['genericOwnerAddressRegion'] . " "
                                        . $identity['genericOwnerPostalCode'] . ", "
                                        . $identity['genericOwnerAddressCountry'];

            $helper['ownerAddressHtml'] = $identity['genericOwnerName'] . "<br />"
                                        . $identity['genericOwnerStreetAddress'] . "<br />"
                                        . $identity['genericOwnerAddressLocality'] . ", " . $identity['genericOwnerAddressRegion'] . " " . $identity['genericOwnerPostalCode'] . "<br />"
                                        . $identity['genericOwnerAddressCountry'] . "<br />";

            $params=array();
            $params = count($params) ? '&' . http_build_query($params) : '';
            $query = urlencode($helper['ownerAddressString']);
            $helper['ownerMapUrl'] = "http://maps.google.com/maps?q={$query}{$params}";
        }
    } /* -- addSIdentityHelpers */

/* --------------------------------------------------------------------------------
    Add the Creator helper strings to 'seomaticHelper'
-------------------------------------------------------------------------------- */

    private function addCreatorHelpers(&$helper, $creator)
    {

/* -- Computed identity strings */

        $now = new DateTime;
        $period = ".";
        $name = $creator['genericCreatorName'];
        if ($name)
        {
            if ($name[strlen($name) -1] == '.')
                $period =" ";
        }
        $helper['creatorCopyrightNotice'] = Craft::t("Copyright") . " &copy;" . $now->year() . " " . $name . $period;

        $helper['creatorAddressString'] = '';
        $helper['creatorAddressHtml'] = '';
        $helper['creatorMapUrl'] = '';
        if ($creator['genericCreatorStreetAddress'] &&
            $creator['genericCreatorAddressLocality'] &&
            $creator['genericCreatorAddressRegion'] &&
            $creator['genericCreatorPostalCode'])
        {
            $helper['creatorAddressString'] = $creator['genericCreatorName'] . ", "
                                        . $creator['genericCreatorStreetAddress'] . ", "
                                        . $creator['genericCreatorAddressLocality'] . ", "
                                        . $creator['genericCreatorAddressRegion'] . " "
                                        . $creator['genericCreatorPostalCode'] . ", "
                                        . $creator['genericCreatorAddressCountry'];

            $helper['creatorAddressHtml'] = $creator['genericCreatorName'] . "<br />"
                                        . $creator['genericCreatorStreetAddress'] . "<br />"
                                        . $creator['genericCreatorAddressLocality'] . ", " . $creator['genericCreatorAddressRegion'] . " " . $creator['genericCreatorPostalCode'] . "<br />"
                                        . $creator['genericCreatorAddressCountry'] . "<br />";

            $params=array();
            $params = count($params) ? '&' . http_build_query($params) : '';
            $query = urlencode($helper['creatorAddressString']);
            $helper['creatorMapUrl'] = "http://maps.google.com/maps?q={$query}{$params}";
        }
    } /* -- addCreatorHelpers */


/* --------------------------------------------------------------------------------
    Get a md5 hash string for this combination of $metaVars
-------------------------------------------------------------------------------- */

    private function getMetaHashStr($templatePath, $metaVars)
    {
        $hashStr = $templatePath;

        $hashStr .= $this->_get_hash_string($metaVars);

        $result = md5($hashStr);

        return $result;
    } /* -- getMetaHashStr */

/* --------------------------------------------------------------------------------
    Sanitize the metaVars
-------------------------------------------------------------------------------- */

    public function sanitizeMetaVars(&$metaVars)
    {
        $seomaticMeta = $metaVars['seomaticMeta'];
        $seomaticSiteMeta = $metaVars['seomaticSiteMeta'];
        $seomaticIdentity = $metaVars['seomaticIdentity'];
        $seomaticSocial = $metaVars['seomaticSocial'];
        $seomaticCreator = $metaVars['seomaticCreator'];

        if (isset($metaVars['seomaticProduct']))
            $seomaticProduct = $metaVars['seomaticCreator'];

/* -- Set up the title prefix and suffix for the OpenGraph and Twitter titles */

        $titlePrefix = "";
        if ($seomaticSiteMeta['siteSeoTitlePlacement'] == "before")
            $titlePrefix =  $seomaticSiteMeta['siteSeoName'] . " " . $seomaticSiteMeta['siteSeoTitleSeparator'] . " ";
        $titleSuffix = "";
        if ($seomaticSiteMeta['siteSeoTitlePlacement'] == "after")
            $titleSuffix = " " . $seomaticSiteMeta['siteSeoTitleSeparator'] . " " . $seomaticSiteMeta['siteSeoName'];

        if (isset($seomaticMeta['twitter']))
            $seomaticMeta['twitter']['title'] = $titlePrefix . $seomaticMeta['seoTitle'] . $titleSuffix;
        if (isset($seomaticMeta['og']))
            $seomaticMeta['og']['title'] = $titlePrefix . $seomaticMeta['seoTitle'] . $titleSuffix;

/* -- Truncate seoTitle, seoDescription, and seoKeywords to recommended values */

        $titleLength = 0;
        if (craft()->config->get("truncateTitleTags", "seomatic"))
        {
            $titleLength = craft()->config->get("maxTitleLength", "seomatic");
            if ($seomaticSiteMeta['siteSeoTitlePlacement'] == "none")
                $titleLength = $titleLength;
            else
                $titleLength = ($titleLength - strlen(" | ") - strlen($seomaticSiteMeta['siteSeoName']));
        }

        $descriptionLength = 0;
        if (craft()->config->get("truncateDescriptionTags", "seomatic"))
            $descriptionLength = craft()->config->get("maxDescriptionLength", "seomatic");

        $keywordsLength = 0;
        if (craft()->config->get("truncateKeywordsTags", "seomatic"))
            $keywordsLength = craft()->config->get("maxKeywordsLength", "seomatic");

        $vars = array('seoTitle' => $titleLength, 'seoDescription' => $descriptionLength, 'seoKeywords' => $keywordsLength);

        foreach ($vars as $key => $value)
        {
            if (isset($seomaticMeta[$key]) && $value)
            {
                $seomaticMeta[$key] = $this->truncateStringOnWord($seomaticMeta[$key], $value);
            }
        }

/* -- Make sure all of our variables are properly encoded */

        $this->sanitizeArray($seomaticMeta);
        $this->sanitizeArray($seomaticSiteMeta);
        $this->sanitizeArray($seomaticIdentity);
        $this->sanitizeArray($seomaticSocial);
        $this->sanitizeArray($seomaticCreator);
        if (isset($metaVars['seomaticProduct']))
            $this->sanitizeArray($seomaticProduct);

        $metaVars['seomaticMeta'] = $seomaticMeta;
        $metaVars['seomaticSiteMeta'] = $seomaticSiteMeta;
        $metaVars['seomaticIdentity'] = $seomaticIdentity;
        $metaVars['seomaticSocial'] = $seomaticSocial;
        $metaVars['seomaticCreator'] = $seomaticCreator;
        if (isset($metaVars['seomaticProduct']))
            $metaVars['seomaticCreator'] = $seomaticProduct;

    } /* -- sanitizeMetaVars */

/* --------------------------------------------------------------------------------
    Get a fully qualified URL based on the siteUrl, if no scheme/host is present
-------------------------------------------------------------------------------- */

public function getFullyQualifiedUrl($url)
{
    $result = $url;
    if (!isset($result) || $result == "")
        return $result;
    $srcUrlParts = parse_url($result);
    if (isset($srcUrlParts['scheme']) && isset($srcUrlParts['host']))
    {
/* -- The URL is already a fully qualfied URL, do nothing */
    }
    else
    {
        $siteUrlOverride = craft()->config->get("siteUrlOverride", "seomatic");
        if ($siteUrlOverride)
            $siteUrl = $siteUrlOverride;
        else
            $siteUrl = craft()->getSiteUrl();

        $urlParts = parse_url($siteUrl);
        if (isset($urlParts['scheme']) && isset($urlParts['host']))
            $siteUrl = $urlParts['scheme'] . "://" . $urlParts['host'] . "/";
        else
            $siteUrl = "/";
        if (($siteUrl[strlen($siteUrl) -1] == '/') && ($result[0] == '/'))
        {
            $siteUrl = rtrim($siteUrl, '/');
        }
        $result = $siteUrl . $result;
    }

    return $result;
} /* -- getFullyQualifiedUrl */

/* --------------------------------------------------------------------------------
    Extract the most important words from the passed in text via TextRank
-------------------------------------------------------------------------------- */

    public function extractKeywords($text = null, $limit = 15, $withoutStopWords = true)
    {
        if (!$text)
            return;
        $text = strtolower($text);
        $config = new Config;
        if ($withoutStopWords)
        {
            $config->addListener(new Stopword);
        }

        $textRank = new TextRank($config);

        try
        {
            $keywords = $textRank->getKeywords(
                $this->_cleanupText($text)
            );
        }
        catch (\RuntimeException $e)
        {
            $keywords = null;
        }

        if ($keywords == "")
            $keywords = str_replace(' ', ',' , $text);
        return (is_array($keywords)) ? implode(", ", array_slice(array_keys($keywords), 0, $limit)) : $keywords;
    } /* -- extractKeywords */

/* --------------------------------------------------------------------------------
    Extract a summary from the text, or if it's not long enough, just return the text
-------------------------------------------------------------------------------- */

    public function extractSummary($text = null, $limit = null, $withoutStopWords = true)
    {
        if (!$text)
            return;
        $config = new Config;
        if ($withoutStopWords)
        {
            $config->addListener(new Stopword);
        }
        $analyzer = new Summary($config);

        try
        {
            $summary = $analyzer->getSummary(
                $this->_cleanupText($text)
            );

            if ($summary && is_integer($limit))
            {
                $summary = mb_strimwidth($summary, 0, $limit, "...");
            }
        }
        catch (\RuntimeException $e)
        {
            $summary = $text;
        }
        if ($summary == "")
            $keywords = $text;

        return $summary;
    } /* -- extractSummary */

/* --------------------------------------------------------------------------------
    Sanitize the passed in array recursively
-------------------------------------------------------------------------------- */

    public function sanitizeArray(&$theArray)
    {
        foreach ($theArray as $key => &$value)
        {
            if (is_object($value))
                $value = (string)$value;
            if (is_string($value))
            {
                $value = craft()->config->parseEnvironmentString($value);
                $value = strip_tags($value);
                if ($key === 'email')
                    $value = $this->encodeEmailAddress($value);
                elseif ($key === 'url')
                    $value = $this->getFullyQualifiedUrl($value);
                else
                    $value = htmlspecialchars($value, ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
                $theArray[$key] = $value;
            }
            else
            {
                if (is_array($value))
                    $this->sanitizeArray($value);
            }
        }
    } /* -- sanitizeArray */

/* --------------------------------------------------------------------------------
    Cleanup text before extracting keywords/summary
-------------------------------------------------------------------------------- */

    private function _cleanupText($text = null)
    {
/* -- convert to UTF-8 */

        $text = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8//IGNORE", $text);

/* -- strip HTML tags */

        $text = preg_replace('#<[^>]+>#', ' ', $text);

/* -- remove excess whitespace */

        $text = preg_replace('/\s+/', ' ', $text);

        return $text;
    } /* -- _cleanupText */

/* --------------------------------------------------------------------------------
    Print out a Twig array, recursing it as necessary
-------------------------------------------------------------------------------- */

    private function _print_twig_array($theArray, $level)
    {
        $htmlText = "";
        $i = 0;
        $len = count($theArray);
        foreach ($theArray as $key => $value)
        {
            if ($i == $len - 1)
                $comma = "";
            else
                $comma = ",";

            if (is_array($value))
            {
                if (empty($value))
                {
                    $line = $key . ": [],\n";
                    $line = str_pad($line, strlen($line) + ($level * 4), " ", STR_PAD_LEFT);
                }
                else
                {
                    $keys = array_keys($value);
                    if ($keys[0] == "0")
                    {
                        $predicate = ": [";
                        $suffix = "]" . $comma  . "\n";
                        $subLines = "";
                        $numSubi = count($value);
                        $subi = 0;
                        foreach ($value as $subValue)
                        {
                            $subi++;
                            if ($subi == $numSubi)
                                $subComma = "";
                            else
                                $subComma = ",";
                            if (is_array($subValue))
                            {
                                $blockOpen = "{";
                                $blockOpen = str_pad($blockOpen, strlen($blockOpen) + (($level+1) * 4), " ", STR_PAD_LEFT);
                                $blockClose = "}" . $subComma;
                                $blockClose = str_pad($blockClose, strlen($blockClose) + (($level+1) * 4), " ", STR_PAD_LEFT);
                                $subLines = $subLines . "\n" . $blockOpen . "\n" . $this->_print_twig_array($subValue, $level + 2) . $blockClose;
                                if ($subi == $numSubi)
                                {
                                    $subLines .= "\n";
                                    $suffix = str_pad($suffix, strlen($suffix) + ($level * 4), " ", STR_PAD_LEFT);
                                }
                            }
                            else
                                $subLines .= "\"" . $subValue . "\"" . $subComma;
                        }
                        if ($level < 1)
                        {
                            $predicate = "{% set " . $key . " = [ ";
                            $suffix = "] %}" . "\n\n";
                            $key = "";
                        }
                        $line =  $key . $predicate;
                        $line = str_pad($line, strlen($line) + ($level * 4), " ", STR_PAD_LEFT);
                        $line = $line . $subLines . $suffix;
                    }
                    else
                    {
                        $predicate = $key . ": { " . "\n";
                        $suffix = $comma;
                        if ($level < 1)
                        {
                            $predicate = "{% set " . $key . " = { " . "\n";
                            $suffix = " %}" . "\n";
                        }
                        $predicate = str_pad($predicate, strlen($predicate) + ($level * 4), " ", STR_PAD_LEFT);
                        $line = $this->_print_twig_array($value, $level + 1);
                        $suffix = "}" . $suffix . "\n";
                        $suffix = str_pad($suffix, strlen($suffix) + ($level * 4), " ", STR_PAD_LEFT);
                        $line = $predicate . $line . $suffix;
                    }
                }
            }
            else
            {
                if ($level < 1)
                    $line = "{% set " . $key . " = \"" . htmlspecialchars($value, ENT_COMPAT | ENT_HTML401, 'UTF-8', false) . "\" %}" . "\n";
                else
                    {
                        $line = $key . ": \"" . htmlspecialchars($value, ENT_COMPAT | ENT_HTML401, 'UTF-8', false) . "\"" . $comma . "\n";
                        $line = str_pad($line, strlen($line) + ($level * 4), " ", STR_PAD_LEFT);
                    }
            }
            $htmlText = $htmlText . $line;
            $i++;
        }

        return $htmlText;
    } /* -- _print_twig_array */

/* --------------------------------------------------------------------------------
    Concatenate all of the values in an array recursively
-------------------------------------------------------------------------------- */

    private function _get_hash_string($theArray)
    {
        $result = "";
        foreach ($theArray as $key => $value)
        {
            if (is_array($value))
                $line = $this->_get_hash_string($value);
            else
                $line = $value;
            $result .= $line;
        }

        return $result;
    } /* -- _get_hash_string */

/* --------------------------------------------------------------------------------
    Truncate the the string passed in, breaking it on a word.  $desiredLength
    is in characters; the returned string will be broken on a whole-word
    boundary, with an … appended to the end if it is truncated
-------------------------------------------------------------------------------- */

    public function truncateStringOnWord($theString, $desiredLength)
    {
        if (strlen($theString) > $desiredLength)
        {

/* -- Force-add a space after commas */

            $theString = preg_replace("/,([^\s])/", ", $1", $theString);

/* -- Wrap the string to the right length */

            $theString = wordwrap($theString, $desiredLength);
            $theString = substr($theString, 0, strpos($theString, "\n"));
            if (substr($theString, -1) == ',')
                $theString = rtrim($theString, ',');
            else
            {
                if (strlen($theString) < $desiredLength)
                    $theString = $theString . "…";
            }
        }

        return $theString;
    } /* -- truncateStringOnWord */

/* --------------------------------------------------------------------------------
    Encode an email address as ordinal values to obfuscate it to bots
-------------------------------------------------------------------------------- */

    public function encodeEmailAddress($emailAddress)
    {
        $result = '';
        for ($i = 0; $i < strlen($emailAddress); $i++)
        {
            $result .= '&#'.ord($emailAddress[$i]).';';
        }

        return $result;
    } /* -- encodeEmailAddress */

    /**
     * Loops through the data and converts the times to DateTime objects.
     *
     * @access private
     * @param array &$value
     */
    public function convertTimes(&$value, $timezone=null)
    {
        if (isset($value) && is_array($value))
        {
            foreach ($value as &$day)
            {
                if ((is_string($day['open']) && $day['open']) || (is_array($day['open']) && $day['open']['time']))
                {
                    $day['open'] = DateTime::createFromString($day['open'], $timezone);
                }
                else
                {
                    $day['open'] = '';
                }

                if ((is_string($day['close']) && $day['close']) || (is_array($day['close']) && $day['close']['time']))
                {
                    $day['close'] = DateTime::createFromString($day['close'], $timezone);
                }
                else
                {
                    $day['close'] = '';
                }
            }
        }
    } /* -- convertTimes */

} /* -- class SeomaticService */
