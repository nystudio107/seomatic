<?php
namespace Craft;

use \DaveChild\TextStatistics as TS;
use \Sunra\PhpSimple\HtmlDomParser;

class SeomaticController extends BaseController
{

    protected $allowAnonymous = array('actionRenderHumans', 'actionRenderRobots', 'actionRenderMetrics');
    protected $parsingDom = false;

/**
 */
    public function actionEditSettings()
    {
        $seomaticPlugin = craft()->plugins->getPlugin('seomatic');
        $settings = $seomaticPlugin->getSettings();

        $this->renderTemplate('seomatic/settings', array(
           'settings' => $settings
        ));
    } /* -- actionEditSettings */

/* --------------------------------------------------------------------------------
    Render the SEO Metrics
-------------------------------------------------------------------------------- */

    public function actionRenderMetrics()
    {

        if (!$this->parsingDom)
        {
            $this->parsingDom = true;
            $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

    /* -- Render the SEOmatic display preview template */

            $url = urldecode(craft()->request->getParam('url'));
            if (UrlHelper::isAbsoluteUrl($url))
            {
                $urlParts = parse_url($url);

                if (isset($urlParts['scheme']))
                    $rootUrl = $urlParts['scheme'] . "://" . $urlParts['host'];
                else
                    $rootUrl = "http" . "://" . $urlParts['host'];
                if (isset($urlParts['port']))
                    $rootUrl .= $urlParts['port'] . "/";
                else
                    $rootUrl .= "/";

                $keywordsParam = urldecode(craft()->request->getParam('keywords'));
                $keywordsKeys = explode(",", $keywordsParam);
                $keywords = array();
    /* -- Silly work-around for what appears to be a file_get_contents bug with https -> http://stackoverflow.com/questions/10524748/why-im-getting-500-error-when-using-file-get-contents-but-works-in-a-browser */
                $opts = array(
                    'ssl'=>array(
                        'verify_peer'=>false,
                        'verify_peer_name'=>false,
                    ),
                    'http'=>array(
                        'ignore_errors' => true,
                        'header' => "User-Agent:Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13\r\n"
                        )
                    );
                $context = stream_context_create($opts);
                $dom = HtmlDomParser::file_get_html($url, false, $context);
                if ($dom)
                {
                    $textStatistics = new TS\TextStatistics;

    /* -- See if robots.txt exists */

                    $hasRobotsTxt = false;
                    $hasSitemap = false;
                    $sitemapUrl = rtrim($rootUrl, '/') . "/sitemap.xml";
                    $foundSitemapUrl = "";

                    $robotsUrl = rtrim($rootUrl, '/') . "/robots.txt";
                    $robots = @file_get_contents($robotsUrl, false, $context);
                    if ($robots !== false)
                    {
                        $hasRobotsTxt = true;
                        $lines = explode("\n", $robots);
                        foreach ($lines as $line)
                        {
                            $line = ltrim($line);

                            $searchStr = 'Sitemap';
                            $pos = strpos($line, $searchStr);
                            if ($pos !== false)
                            {
                                $pos += strlen($searchStr);
                                $foundSitemapUrl = substr($line, $pos);
                                $foundSitemapUrl = trim($sitemapUrl, ':');
                                $foundSitemapUrl = trim($sitemapUrl);
                            }
                        }
                    }

    /* -- Check to see if a sitemap exists */

                    if ($foundSitemapUrl)
                    {
                        $siteMapContents = "";
                        $siteMapContents = @file_get_contents($sitemapUrl, false, $context, 0, 1);
                        if ($siteMapContents !== false)
                            $hasSitemap = true;
                    }

                    $siteMapContents = "";
                    $siteMapContents = @file_get_contents($sitemapUrl, false, $context, 0, 1);
                    if ($siteMapContents !== false)
                        $hasSitemap = true;

/* -- See if the site is https */

                    $sslReturnCode = 0;
                    $sslUrl = "https" . "://" . $urlParts['host'];
                    if (isset($urlParts['port']))
                        $sslUrl .= $sslUrl['port'] . '/';
                    else
                        $sslUrl .= '/';

                    $ch = curl_init($sslUrl);

                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $open_basedir = ini_get('open_basedir');
                    if (empty($open_basedir))
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                    curl_exec($ch);
                    $sslReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

    /* -- Check to see if the page is valid */

                    $validatorUrl = "https://validator.w3.org/check?uri=" . urlencode($url) . "&output=json";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $validatorUrl);
                    $validatorResult = curl_exec($ch);
                    curl_close($ch);

                    $validatorStatus = $validatorErrors = $validatorWarnings = "";
                    if ($validatorResult)
                    {
                        $searchStr = "X-W3C-Validator-Status: ";
                        $pos = strpos($validatorResult, $searchStr);
                        if ($pos !== false)
                        {
                            $pos += strlen($searchStr);
                            $validatorStatus = substr($validatorResult, $pos, ( strpos($validatorResult, PHP_EOL, $pos) ) - $pos);
                        }

                        $searchStr = "X-W3C-Validator-Errors: ";
                        $pos = strpos($validatorResult, $searchStr);
                        if ($pos !== false)
                        {
                            $pos += strlen($searchStr);
                            $validatorErrors = substr($validatorResult, $pos, ( strpos($validatorResult, PHP_EOL, $pos) ) - $pos);
                        }

                        $searchStr = "X-W3C-Validator-Warnings: ";
                        $pos = strpos($validatorResult, $searchStr);
                        if ($pos !== false)
                        {
                            $pos += strlen($searchStr);
                            $validatorWarnings = substr($validatorResult, $pos, ( strpos($validatorResult, PHP_EOL, $pos) ) - $pos);
                        }
                    }
                    $validatorUrl = "https://validator.w3.org/check?uri=" . urlencode($url);

    /* -- Check Google Pagespeed insights for desktop */

                    $pagespeedDesktopScore = "";
                    $pagespeedDesktopUrl = "https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=" . urlencode($url) . "&strategy=desktop";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $pagespeedDesktopUrl);
                    $pagespeedDesktopResult = curl_exec($ch);
                    curl_close($ch);
                    $pageSpeedPageStats = array();
                    if ($pagespeedDesktopResult)
                    {
                        $pagespeedJson = json_decode($pagespeedDesktopResult, true);
                        if ($pagespeedJson)
                        {
                            if (!empty($pagespeedJson['pageStats']))
                            {
                                $pageSpeedPageStats = $pagespeedJson['pageStats'];
                                if (empty($pageSpeedPageStats['htmlResponseBytes']))
                                    $pageSpeedPageStats['htmlResponseBytes'] = 0;
                                if (empty($pageSpeedPageStats['cssResponseBytes']))
                                    $pageSpeedPageStats['cssResponseBytes'] = 0;
                                if (empty($pageSpeedPageStats['imageResponseBytes']))
                                    $pageSpeedPageStats['imageResponseBytes'] = 0;
                                if (empty($pageSpeedPageStats['javascriptResponseBytes']))
                                    $pageSpeedPageStats['javascriptResponseBytes'] = 0;
                                if (empty($pageSpeedPageStats['otherResponseBytes']))
                                    $pageSpeedPageStats['otherResponseBytes'] = 0;
                                $pageSpeedPageStats['totalResponseBytes'] = $pageSpeedPageStats['htmlResponseBytes'] +
                                    $pageSpeedPageStats['cssResponseBytes'] +
                                    $pageSpeedPageStats['imageResponseBytes'] +
                                    $pageSpeedPageStats['javascriptResponseBytes'] +
                                    $pageSpeedPageStats['otherResponseBytes'];
                            }

                            if (isset($pagespeedJson['responseCode']) && ($pagespeedJson['responseCode'] == "200" || $pagespeedJson['responseCode'] == "301" || $pagespeedJson['responseCode'] == "302"))
                            {
                                if (isset($pagespeedJson['ruleGroups']['SPEED']['score']))
                                    $pagespeedDesktopScore = intval($pagespeedJson['ruleGroups']['SPEED']['score']);
                            }
                        }
                    }
                    $pagespeedDesktopUrl = "https://developers.google.com/speed/pagespeed/insights/?url=" . urlencode($url) . "&tab=desktop";

    /* -- Check Google Pagespeed insights for desktop */

                    $pagespeedMobileScore = "";
                    $pagespeedMobileUsability = "";
                    $pagespeedMobileUrl = "https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=" . urlencode($url) . "&strategy=mobile";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                    curl_setopt($ch, CURLOPT_URL, $pagespeedMobileUrl);
                    $pagespeedMobileResult = curl_exec($ch);
                    curl_close($ch);
                    if ($pagespeedMobileResult)
                    {
                        $pagespeedJson = json_decode($pagespeedMobileResult, true);
                        if ($pagespeedJson)
                        {
                            if (isset($pagespeedJson['responseCode']) && ($pagespeedJson['responseCode'] == "200" || $pagespeedJson['responseCode'] == "301" || $pagespeedJson['responseCode'] == "302"))
                            {
                                if (isset($pagespeedJson['ruleGroups']['SPEED']['score']))
                                    $pagespeedMobileScore = intval($pagespeedJson['ruleGroups']['SPEED']['score']);
                                if (isset($pagespeedJson['ruleGroups']['USABILITY']['score']))
                                    $pagespeedMobileUsability = intval($pagespeedJson['ruleGroups']['USABILITY']['score']);
                            }
                        }
                    }
                    $pagespeedMobileUrl = "https://developers.google.com/speed/pagespeed/insights/?url=" . urlencode($url) . "&tab=mobile";

    /* -- Scrape for JSON-LD before we remove the <script> tags */

                    $jsonLdTypes = array();
                    foreach($dom->find('script[type=application/ld+json]') as $elem)
                    {
                        $jsonArray = json_decode($elem->innertext, true);
                        if (isset($jsonArray['@type']))
                            array_push($jsonLdTypes, $jsonArray['@type']);
                    }
                    $jsonLdTypes = array_unique($jsonLdTypes);

    /* -- Remove inline <script> and <style> tags, and then strip the DOM down */

                    foreach($dom->find('style') as $element)
                        $element->outertext = '';
                    foreach($dom->find('script') as $element)
                        $element->outertext = '';
                    $strippedDom = html_entity_decode($dom->plaintext);
//                    $strippedDom = preg_replace('@[^0-9a-z\.\!]+@i', ', ', $strippedDom);
                    $strippedDom = stripslashes($strippedDom);
                    $htmlDom = html_entity_decode($dom->outertext);
//                    $htmlDom = preg_replace('@[^0-9a-z\.\!]+@i', '', $htmlDom);

    /* -- SEO statistics */

                    $titleTag = html_entity_decode($dom->find('title', 0)->plaintext);
                    $titleLength = strlen($titleTag);

                    $metaDescriptionTag = "";
                    $metaDescriptionLength = 0;
                    $elem = $dom->find('meta[name=description]', 0);
                    if ($elem)
                    {
                        $metaDescriptionTag = html_entity_decode($elem->content);
                        $metaDescriptionLength = strlen($metaDescriptionTag);
                    }

                    $metaTwitterTag = "";
                    $elem = $dom->find('meta[name=twitter:card],meta[property=twitter:card]', 0);
                    if ($elem)
                        $metaTwitterTag = html_entity_decode($elem->content);

                    $metaOpenGraphTag = "";
                    $elem = $dom->find('meta[property=og:type],meta[property=og:url],meta[property=og:title]', 0);
                    if ($elem)
                        $metaOpenGraphTag = html_entity_decode($elem->content);

                    $hasRelPublisherTag = false;
                    $elem = $dom->find('link[rel=publisher]', 0);
                    if ($elem)
                        $hasRelPublisherTag = true;

                    $emptyImageAlts = count($dom->find('img[!alt]'));

                    $h1Tags = count($dom->find('h1'));
                    $h2Tags = count($dom->find('h2'));
                    $h3Tags = count($dom->find('h3'));
                    $h4Tags = count($dom->find('h4'));
                    $h5Tags = count($dom->find('h5'));
                    $totalHTags = $h1Tags + $h2Tags + $h3Tags + $h4Tags + $h5Tags;
                    $effectiveHTags = true;
                    if ($h1Tags != 1)
                        $effectiveHTags = false;
                    if ($totalHTags < 3)
                        $effectiveHTags = false;
                    if ($h2Tags == 0 && ($h3Tags || $h4Tags || $h5Tags))
                        $effectiveHTags = false;
                    if ($h3Tags == 0 && ($h4Tags || $h5Tags))
                        $effectiveHTags = false;
                    if ($h4Tags == 0 && ($h5Tags))
                        $effectiveHTags = false;

                    $textToHtmlRatio = (strlen($strippedDom) / (strlen($htmlDom) - strlen($strippedDom))) * 100;

                    $strippedDom = preg_replace('/\s+/', ' ', $strippedDom);

/* -- Extract the page keywords, and clean them up a bit */

                    $pageKeywords = craft()->seomatic->extractKeywords($strippedDom);

                    $pageKeywords = str_replace(",,",",", $pageKeywords);
                    $pageKeywords = str_replace(" ,",",", $pageKeywords);
                    $pageKeywords = str_replace(" .",".", $pageKeywords);
                    $pageKeywords = preg_replace('/\.+/', '.', $pageKeywords);
                    $pageKeywords = preg_replace('/,+/', ',', $pageKeywords);
                    $pageKeywords = str_replace(",.,",",", $pageKeywords);
                    $pageKeywords = html_entity_decode($pageKeywords, ENT_COMPAT, 'UTF-8');

    /* -- Focus keywords */

                    foreach ($keywordsKeys as $keywordsKey)
                    {
                        $keywordsKey = trim($keywordsKey);
                        if (strlen($keywordsKey))
                        {
                            $appearsInH1Tag = 0;
                            foreach($dom->find('h1') as $element)
                                $appearsInH1Tag += substr_count(strtolower($element->plaintext), strtolower($keywordsKey));
                            foreach($dom->find('h2') as $element)
                                $appearsInH1Tag += substr_count(strtolower($element->plaintext), strtolower($keywordsKey));

                            $appearsInImgTag = 0;
                            foreach($dom->find('img') as $element)
                                $appearsInImgTag += substr_count(strtolower($element->alt), strtolower($keywordsKey));

                            $appearsInAhrefTag = 0;
                            foreach($dom->find('a') as $element)
                                $appearsInAhrefTag += substr_count(strtolower($element->plaintext), strtolower($keywordsKey));

                            $keywords[$keywordsKey] = array(
                                'appearsInTitleTag' => substr_count(strtolower($titleTag), strtolower($keywordsKey)),
                                'appearsInUrl' => substr_count(strtolower($url), strtolower($keywordsKey)),
                                'appearsInMetaDescriptionTag' => substr_count(strtolower($metaDescriptionTag), strtolower($keywordsKey)),
                                'appearsInH1Tag' => $appearsInH1Tag,
                                'appearsInAhrefTag' => $appearsInAhrefTag,
                                'appearsInImgTag' => $appearsInImgTag,
                                'appearsInPageKeywords' => substr_count(strtolower($pageKeywords), strtolower($keywordsKey)),
                                'appearsOnWebPage' => substr_count(strtolower($strippedDom), strtolower($keywordsKey)),
                                );
                        }
                    }
    /* -- Text statistics */

                    $wordCount = $textStatistics->wordCount($strippedDom);
                    $readingTime = floor($wordCount / 200);
                    if ($readingTime === 0)
                        $readingTime = 1;
                    $fleschKincaidReadingEase = $textStatistics->fleschKincaidReadingEase($strippedDom);
                    $fleschKincaidGradeLevel = $textStatistics->fleschKincaidGradeLevel($strippedDom);
                    $gunningFogScore = $textStatistics->gunningFogScore($strippedDom);
                    $colemanLiauIndex = $textStatistics->colemanLiauIndex($strippedDom);
                    $smogIndex = $textStatistics->smogIndex($strippedDom);
                    $automatedReadabilityIndex = $textStatistics->automatedReadabilityIndex($strippedDom);

                    $vars = array(
                        'titleTag' => $titleTag,
                        'titleLength' => $titleLength,
                        'metaDescriptionTag' => $metaDescriptionTag,
                        'metaDescriptionLength' => $metaDescriptionLength,
                        'metaTwitterTag' => $metaTwitterTag,
                        'metaOpenGraphTag' => $metaOpenGraphTag,
                        'hasRelPublisherTag' => $hasRelPublisherTag,
                        'jsonLdTypes' => $jsonLdTypes,
                        'hasRobotsTxt' => $hasRobotsTxt,
                        'hasSitemap' => $hasSitemap,
                        'emptyImageAlts' => $emptyImageAlts,
                        'validatorUrl' => $validatorUrl,
                        'validatorStatus' => $validatorStatus,
                        'validatorErrors' => $validatorErrors,
                        'validatorWarnings' => $validatorWarnings,
                        'pageSpeedPageStats' => $pageSpeedPageStats,
                        'pagespeedDesktopScore' => $pagespeedDesktopScore,
                        'pagespeedDesktopUrl' => $pagespeedDesktopUrl,
                        'pagespeedMobileScore' => $pagespeedMobileScore,
                        'pagespeedMobileUsability' => $pagespeedMobileUsability,
                        'pagespeedMobileUrl' => $pagespeedMobileUrl,
                        'sslReturnCode' => $sslReturnCode,
                        'h1Tags' => $h1Tags,
                        'h2Tags' => $h2Tags,
                        'h3Tags' => $h3Tags,
                        'h4Tags' => $h4Tags,
                        'h5Tags' => $h5Tags,
                        'effectiveHTags' => $effectiveHTags,
                        'textToHtmlRatio' => $textToHtmlRatio,
                        'wordCount' => $wordCount,
                        'readingTime' => $readingTime,
                        'pageKeywords' => $pageKeywords,
                        'keywords' => $keywords,
                        'fleschKincaidReadingEase' => $fleschKincaidReadingEase,
                        'fleschKincaidGradeLevel' => $fleschKincaidGradeLevel,
                        'gunningFogScore' => $gunningFogScore,
                        'colemanLiauIndex' => $colemanLiauIndex,
                        'smogIndex' => $smogIndex,
                        'automatedReadabilityIndex' => $automatedReadabilityIndex,
                        );

                    //$htmlText = craft()->templates->render('_seo_metrics.twig', $vars);
                    $this->renderTemplate('_seo_metrics.twig', $vars);
                }
                else
                    $this->renderTemplate('_error', array('errorMessage' => "Error parsing the DOM.  Is this a valid, publicly accessible URL?"));
            }
            else
                $this->renderTemplate('_error', array('errorMessage' => "Error loading the webpage. Is this a valid, publicly accessible URL?"));
            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);
        }
        $this->parsingDom = false;
    } /* -- actionRenderMetrics */

/* --------------------------------------------------------------------------------
    Render the humans.txt template
-------------------------------------------------------------------------------- */

    public function actionRenderHumans(array $variables = array())
    {
        $templatePath = '';
        $locale = '';
        if (!$locale)
            $locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);

/* -- Tell Twig not to escape at all for this text template */

        $twig = craft()->templates->getTwig();
        $escaper = $twig->getExtension('escaper');
        $escaper->setDefaultStrategy(false);

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
            $this->renderTemplate($templateName, $metaVars);

            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);
        }
    } /* -- actionRenderHumans */

/* --------------------------------------------------------------------------------
    Render the robots.txt template
-------------------------------------------------------------------------------- */

    public function actionRenderRobots(array $variables = array())
    {
        $templatePath = '';
        $locale = '';
        if (!$locale)
            $locale = craft()->language;
        $metaVars = craft()->seomatic->getGlobals('', $locale);

/* -- Tell Twig not to escape at all for this text template */

        $twig = craft()->templates->getTwig();
        $escaper = $twig->getExtension('escaper');
        $escaper->setDefaultStrategy(false);

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
            $this->renderTemplate($templateName, $metaVars);

            craft()->path->setTemplatesPath($oldPath);
            method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);
        }
    } /* -- actionRenderRobots */

/* --------------------------------------------------------------------------------
    Edit the SiteMeta record
-------------------------------------------------------------------------------- */

    public function actionEditSiteMeta(array $variables = array())
    {

        if (isset($variables['locale']))
            $locale = $variables['locale'];
        else
            $locale = craft()->language;

        $siteMeta = craft()->seomatic->getSiteMeta($locale);
        $titleLength = craft()->config->get("maxTitleLength", "seomatic");
        if ($siteMeta['siteSeoTitlePlacement'] == "none")
            $variables['titleLength'] = $titleLength;
        else
            $variables['titleLength'] = ($titleLength - strlen(" | ") - strlen($siteMeta['siteSeoName']));

        $variables['siteMeta'] = $siteMeta;

        // Whether any assets sources exist
        $sources = craft()->assets->findFolders();
        $variables['assetsSourceExists'] = count($sources);

        // URL to create a new assets source
        $variables['newAssetsSourceUrl'] = UrlHelper::getUrl('settings/assets/sources/new');

        // Set asset ID
        $variables['siteSeoImageId'] = $variables['siteMeta']['siteSeoImageId'];

        // Set asset elements
        if ($variables['siteSeoImageId']) {
            if (is_array($variables['siteSeoImageId'])) {
                $variables['siteSeoImageId'] = $variables['siteSeoImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['siteSeoImageId']);
            $variables['elements'] = array($asset);
        } else {
            $variables['elements'] = array();
        }

        // Set asset ID
        $variables['siteSeoTwitterImageId'] = $variables['siteMeta']['siteSeoTwitterImageId'];

        // Set asset elements
        if ($variables['siteSeoTwitterImageId']) {
            if (is_array($variables['siteSeoTwitterImageId'])) {
                $variables['siteSeoTwitterImageId'] = $variables['siteSeoTwitterImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['siteSeoTwitterImageId']);
            $variables['elementsTwitter'] = array($asset);
        } else {
            $variables['elementsTwitter'] = array();
        }

        // Set asset ID
        $variables['siteSeoFacebookImageId'] = $variables['siteMeta']['siteSeoFacebookImageId'];

        // Set asset elements
        if ($variables['siteSeoFacebookImageId']) {
            if (is_array($variables['siteSeoFacebookImageId'])) {
                $variables['siteSeoFacebookImageId'] = $variables['siteSeoFacebookImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['siteSeoFacebookImageId']);
            $variables['elementsFacebook'] = array($asset);
        } else {
            $variables['elementsFacebook'] = array();
        }

        // Set element type
        $variables['elementType'] = craft()->elements->getElementType(ElementType::Asset);

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'seomatic/site';
        $variables['transformsList'] = craft()->seomatic->getTransformsList();

        // Render the template!
        $this->renderTemplate('seomatic/site/_edit', $variables);
    } /* -- actionEditSiteMeta */

/* --------------------------------------------------------------------------------
    Edit the Identity record
-------------------------------------------------------------------------------- */

    public function actionEditIdentity(array $variables = array())
    {

        if (isset($variables['locale']))
            $locale = $variables['locale'];
        else
            $locale = craft()->language;
        $variables['identity'] = craft()->seomatic->getIdentity($locale);

/* -- LocalBusiness owner fields https://schema.org/LocalBusiness */

        // Whether any assets sources exist
        $sources = craft()->assets->findFolders();
        $variables['assetsSourceExists'] = count($sources);

        // URL to create a new assets source
        $variables['newAssetsSourceUrl'] = UrlHelper::getUrl('settings/assets/sources/new');

        // Set asset ID
        $variables['genericOwnerImageId'] = $variables['identity']['genericOwnerImageId'];

        // Set asset elements
        if ($variables['genericOwnerImageId']) {
            if (is_array($variables['genericOwnerImageId'])) {
                $variables['genericOwnerImageId'] = $variables['genericOwnerImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['genericOwnerImageId']);
            $variables['elementsOwnerImage'] = array($asset);
        } else {
            $variables['elementsOwnerImage'] = array();
        }

        // Set element type
        $variables['elementType'] = craft()->elements->getElementType(ElementType::Asset);

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'seomatic/identity';

        // Render the template!
        $this->renderTemplate('seomatic/identity/_edit', $variables);
    } /* -- actionEditIdentity */

/* --------------------------------------------------------------------------------
    Edit the Social record
-------------------------------------------------------------------------------- */

    public function actionEditSocial(array $variables = array())
    {

        if (isset($variables['locale']))
            $locale = $variables['locale'];
        else
            $locale = craft()->language;
       $variables['social'] = craft()->seomatic->getSocial($locale);

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'seomatic/social';

        // Render the template!
        $this->renderTemplate('seomatic/social/_edit', $variables);
    } /* -- actionEditSocial */

/* --------------------------------------------------------------------------------
    Edit the Creator record
-------------------------------------------------------------------------------- */

    public function actionEditCreator(array $variables = array())
    {

        if (isset($variables['locale']))
            $locale = $variables['locale'];
        else
            $locale = craft()->language;

        $variables['creator'] = craft()->seomatic->getCreator($locale);

        // Whether any assets sources exist
        $sources = craft()->assets->findFolders();
        $variables['assetsSourceExists'] = count($sources);

        // URL to create a new assets source
        $variables['newAssetsSourceUrl'] = UrlHelper::getUrl('settings/assets/sources/new');

        // Set asset ID
        $variables['genericCreatorImageId'] = $variables['creator']['genericCreatorImageId'];

        // Set asset elements
        if ($variables['genericCreatorImageId']) {
            if (is_array($variables['genericCreatorImageId'])) {
                $variables['genericCreatorImageId'] = $variables['genericCreatorImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['genericCreatorImageId']);
            $variables['elementsCreatorImage'] = array($asset);
        } else {
            $variables['elementsCreatorImage'] = array();
        }

        // Set element type
        $variables['elementType'] = craft()->elements->getElementType(ElementType::Asset);

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'seomatic/creator';

        // Render the template!
        $this->renderTemplate('seomatic/creator/_edit', $variables);
    } /* -- actionEditCreator */

/* ================================================================================
    META ElementTypes
================================================================================ */


/* --------------------------------------------------------------------------------
    Edit a template Meta
-------------------------------------------------------------------------------- */

    public function actionEditMeta(array $variables = array())
    {
        if (isset($variables['locale']))
            $locale = $variables['locale'];
        else
            $locale = craft()->language;

        $siteMeta = craft()->seomatic->getSiteMeta($locale);
        $titleLength = craft()->config->get("maxTitleLength", "seomatic");
        if ($siteMeta['siteSeoTitlePlacement'] == "none")
            $variables['titleLength'] = $titleLength;
        else
            $variables['titleLength'] = ($titleLength - strlen(" | ") - strlen($siteMeta['siteSeoName']));

        if (empty($variables['meta']))
        {
            if (!empty($variables['metaId']))
            {
                $variables['meta'] = craft()->seomatic->getMetaById($variables['metaId'], $locale);

/* -- If we have a metaId but nothing is returned for that locale, make a new element/record using that elementId */

                if (!$variables['meta'])
                {
                    /*
                    $variables['meta'] = new Seomatic_MetaModel();
                    $variables['meta']['locale'] = $locale;
                    $variables['meta']['elementId'] = $variables['metaId'];
                    */
                    throw new HttpException(404);
                }
            }
            else
            {
                $variables['meta'] = new Seomatic_MetaModel();
                $variables['meta']['locale'] = $locale;
            }
        }

        // Whether any assets sources exist
        $sources = craft()->assets->findFolders();
        $variables['assetsSourceExists'] = count($sources);

        $variables['transformsList'] = craft()->seomatic->getTransformsList();

        // URL to create a new assets source
        $variables['newAssetsSourceUrl'] = UrlHelper::getUrl('settings/assets/sources/new');

        // Set asset ID
        $variables['seoImageId'] = $variables['meta']->seoImageId;

        // Set asset elements
        if ($variables['seoImageId']) {
            if (is_array($variables['seoImageId'])) {
                $variables['seoImageId'] = $variables['seoImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['seoImageId']);
            $variables['elements'] = array($asset);
        } else {
            $variables['elements'] = array();
        }

        // Set asset ID
        $variables['seoTwitterImageId'] = $variables['meta']->seoTwitterImageId;

        // Set asset elements
        if ($variables['seoTwitterImageId']) {
            if (is_array($variables['seoTwitterImageId'])) {
                $variables['seoTwitterImageId'] = $variables['seoTwitterImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['seoTwitterImageId']);
            $variables['elementsTwitter'] = array($asset);
        } else {
            $variables['elementsTwitter'] = array();
        }

        // Set asset ID
        $variables['seoFacebookImageId'] = $variables['meta']->seoFacebookImageId;

        // Set asset elements
        if ($variables['seoFacebookImageId']) {
            if (is_array($variables['seoFacebookImageId'])) {
                $variables['seoFacebookImageId'] = $variables['seoFacebookImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['seoFacebookImageId']);
            $variables['elementsFacebook'] = array($asset);
        } else {
            $variables['elementsFacebook'] = array();
        }

        // Set element type
        $variables['elementType'] = craft()->elements->getElementType(ElementType::Asset);

        // Tabs
        $variables['tabs'] = array();

        if (!$variables['meta']->id)
        {
            $variables['title'] = Craft::t('Untitled Meta');
        }
        else
        {
            $variables['title'] = $variables['meta']->title;
        }

        // Breadcrumbs
        $variables['crumbs'] = array(
            array('label' => Craft::t('SEO Template Meta'), 'url' => UrlHelper::getUrl('seomatic/meta')),
        );

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'seomatic/meta/{id}/{locale}';

        // Render the template!
        $this->renderTemplate('seomatic/meta/_edit', $variables);
    } /* -- actionEditMeta */

/* --------------------------------------------------------------------------------
    Save a meta
-------------------------------------------------------------------------------- */

    public function actionSaveMeta()
    {
        $this->requirePostRequest();

        $metaId = craft()->request->getPost('metaId');
        $locale = craft()->request->getPost('locale');
        /*
        $elementId = craft()->request->getPost('elementId');
        */

        if ($metaId)
        {
            $model = craft()->seomatic->getMetaById($metaId, $locale);

            if (!$model)
            {
                /*
                $model = new Seomatic_MetaModel();
                $elementId = $metaId;
                */
                throw new Exception(Craft::t('No meta exists with the ID “{id}”', array('id' => $metaId)));
            }
        }
        else
        {
            $model = new Seomatic_MetaModel();
        }

/* -- Set the Meta attributes, defaulting to the existing values for whatever is missing from the post data */

        $model->locale = craft()->request->getPost('locale', $locale);
        $model->elementId = $metaId;
        $model->metaType = craft()->request->getPost('metaType', $model->metaType);
        $model->metaPath = craft()->request->getPost('metaPath', $model->metaPath);
        $model->seoMainEntityCategory = craft()->request->getPost('seoMainEntityCategory', $model->seoMainEntityCategory);
        $model->seoMainEntityOfPage = craft()->request->getPost('seoMainEntityOfPage', $model->seoMainEntityOfPage);
        $model->seoTitle = craft()->request->getPost('seoTitle', $model->seoTitle);
        $model->seoDescription = craft()->request->getPost('seoDescription', $model->seoDescription);
        $model->seoKeywords = craft()->request->getPost('seoKeywords', $model->seoKeywords);
        $model->seoImageTransform = craft()->request->getPost('seoImageTransform', $model->seoImageTransform);
        $model->seoFacebookImageTransform = craft()->request->getPost('seoFacebookImageTransform', $model->seoFacebookImageTransform);
        $model->seoTwitterImageTransform = craft()->request->getPost('seoTwitterImageTransform', $model->seoTwitterImageTransform);
        $model->twitterCardType = craft()->request->getPost('twitterCardType', $model->twitterCardType);
        $model->openGraphType = craft()->request->getPost('openGraphType', $model->openGraphType);
        $model->robots = craft()->request->getPost('robots', $model->robots);
        $model->seoImageId = craft()->request->getPost('seoImageId', $model->seoImageId);
        $model->seoTwitterImageId = craft()->request->getPost('seoTwitterImageId', $model->seoTwitterImageId);
        $model->seoFacebookImageId = craft()->request->getPost('seoFacebookImageId', $model->seoFacebookImageId);
        $model->enabled = (bool)craft()->request->getPost('enabled', $model->enabled);
        $model->getContent()->title = craft()->request->getPost('title', $model->title);

        if (craft()->seomatic->saveMeta($model))
        {
            craft()->userSession->setNotice(Craft::t('SEOmatic Meta saved.'));
            $this->redirectToPostedUrl($model);
        }
        else
        {
            craft()->userSession->setError(Craft::t('Couldn’t save SEOmatic Meta.'));

/* -- Send the Meta back to the template */

            craft()->urlManager->setRouteVariables(array(
                'meta' => $model
            ));
        }
    } /* -- actionSaveMeta */

/* --------------------------------------------------------------------------------
    Save a meta
-------------------------------------------------------------------------------- */

    public function actionDeleteMeta()
    {
        $this->requirePostRequest();

        $metaId = craft()->request->getRequiredPost('metaId');

        if (craft()->elements->deleteElementById($metaId))
        {
            craft()->userSession->setNotice(Craft::t('SEOmatic Meta deleted.'));
            $this->redirectToPostedUrl();
        }
        else
        {
            craft()->userSession->setError(Craft::t('Couldn’t delete SEOmatic Meta.'));
        }
    } /* -- actionDeleteMeta */

/* ================================================================================
    SITEMETA records
================================================================================ */

/* --------------------------------------------------------------------------------
    Save the SiteMeta record
-------------------------------------------------------------------------------- */

    public function actionSaveSiteMeta()
    {
        $this->requirePostRequest();
        $locale = craft()->request->getPost('locale');
        if (!$locale)
            $locale = craft()->language;

        $record = Seomatic_SettingsRecord::model()->findByAttributes(array(
            'locale' => $locale,
            ));

        if (!$record)
        {
            throw new Exception(Craft::t('No SEOmatic Site Meta exists'));
        }

/* -- Set the SiteMeta attributes, defaulting to the existing values for whatever is missing from the post data */

        $record->siteSeoName = craft()->request->getPost('siteSeoName', $record->siteSeoName);
        $record->siteSeoTitle = craft()->request->getPost('siteSeoTitle', $record->siteSeoTitle);
        $record->siteSeoTitleSeparator = craft()->request->getPost('siteSeoTitleSeparator', $record->siteSeoTitleSeparator);
        $record->siteSeoTitlePlacement = craft()->request->getPost('siteSeoTitlePlacement', $record->siteSeoTitlePlacement);
        $record->siteSeoDescription = craft()->request->getPost('siteSeoDescription', $record->siteSeoDescription);
        $record->siteSeoKeywords = craft()->request->getPost('siteSeoKeywords', $record->siteSeoKeywords);
        $record->siteSeoImageTransform = craft()->request->getPost('siteSeoImageTransform', $record->siteSeoImageTransform);
        $record->siteSeoFacebookImageTransform = craft()->request->getPost('siteSeoFacebookImageTransform', $record->siteSeoFacebookImageTransform);
        $record->siteSeoTwitterImageTransform = craft()->request->getPost('siteSeoTwitterImageTransform', $record->siteSeoTwitterImageTransform);
        $record->siteTwitterCardType = craft()->request->getPost('siteTwitterCardType', $record->siteTwitterCardType);
        $record->siteOpenGraphType = craft()->request->getPost('siteOpenGraphType', $record->siteOpenGraphType);
        $record->siteRobots = craft()->request->getPost('siteRobots', $record->siteRobots);

        $record->siteLinksSearchTargets = craft()->request->getPost('siteLinksSearchTargets', array());
        $record->siteLinksQueryInput = craft()->request->getPost('siteLinksQueryInput', $record->siteLinksQueryInput);

        $record->siteRobotsTxt = craft()->request->getPost('siteRobotsTxt', $record->siteRobotsTxt);

        $record->siteSeoImageId = craft()->request->getPost('siteSeoImageId', $record->siteSeoImageId);
        $assetId = (!empty($record->siteSeoImageId) ? $record->siteSeoImageId[0] : null);
        $record->siteSeoImageId = $assetId;

        $record->siteSeoTwitterImageId = craft()->request->getPost('siteSeoTwitterImageId', $record->siteSeoTwitterImageId);
        $assetId = (!empty($record->siteSeoTwitterImageId) ? $record->siteSeoTwitterImageId[0] : null);
        $record->siteSeoTwitterImageId = $assetId;

        $record->siteSeoFacebookImageId = craft()->request->getPost('siteSeoFacebookImageId', $record->siteSeoFacebookImageId);
        $assetId = (!empty($record->siteSeoFacebookImageId) ? $record->siteSeoFacebookImageId[0] : null);
        $record->siteSeoFacebookImageId = $assetId;

        if ($record->save())
        {
            craft()->userSession->setNotice(Craft::t('SEOmatic Site Meta saved.'));
            $this->redirectToPostedUrl($record);
        }
        else
        {
            craft()->userSession->setError(Craft::t('Couldn’t save SEOmatic Site Meta.'));
            $this->redirectToPostedUrl($record);
        }
    } /* -- actionSaveSiteMeta */

/* ================================================================================
    IDENTITY records
================================================================================ */

/* --------------------------------------------------------------------------------
    Save the Identity record
-------------------------------------------------------------------------------- */

    public function actionSaveIdentity()
    {
        $this->requirePostRequest();

        $locale = craft()->request->getPost('locale');
        if (!$locale)
            $locale = craft()->language;

        $record = Seomatic_SettingsRecord::model()->findByAttributes(array(
            'locale' => $locale,
            ));

        if (!$record)
        {
            throw new Exception(Craft::t('No SEOmatic Settings record exists'));
        }

/* -- Set the Identity attributes, defaulting to the existing values for whatever is missing from the post data */

        $record->googleSiteVerification = craft()->request->getPost('googleSiteVerification', $record->googleSiteVerification);
        $record->bingSiteVerification = craft()->request->getPost('bingSiteVerification', $record->bingSiteVerification);
        $record->googleAnalyticsUID = craft()->request->getPost('googleAnalyticsUID', $record->googleAnalyticsUID);
        $record->googleTagManagerID = craft()->request->getPost('googleTagManagerID', $record->googleTagManagerID);
        $record->googleAnalyticsSendPageview = craft()->request->getPost('googleAnalyticsSendPageview', $record->googleAnalyticsSendPageview);
        $record->googleAnalyticsAdvertising = craft()->request->getPost('googleAnalyticsAdvertising', $record->googleAnalyticsAdvertising);
        $record->googleAnalyticsEcommerce = craft()->request->getPost('googleAnalyticsEcommerce', $record->googleAnalyticsEcommerce);
        $record->googleAnalyticsEEcommerce = craft()->request->getPost('googleAnalyticsEEcommerce', $record->googleAnalyticsEEcommerce);
        $record->googleAnalyticsLinkAttribution = craft()->request->getPost('googleAnalyticsLinkAttribution', $record->googleAnalyticsLinkAttribution);
        $record->googleAnalyticsLinker = craft()->request->getPost('googleAnalyticsLinker', $record->googleAnalyticsLinker);
        $record->googleAnalyticsAnonymizeIp = craft()->request->getPost('googleAnalyticsAnonymizeIp', $record->googleAnalyticsAnonymizeIp);
        $record->siteOwnerType = craft()->request->getPost('siteOwnerType', $record->siteOwnerType);
        $record->siteOwnerSubType = craft()->request->getPost('siteOwnerSubType', $record->siteOwnerSubType);
        $record->siteOwnerSpecificType = craft()->request->getPost('siteOwnerSpecificType', $record->siteOwnerSpecificType);

/* -- Generic owner fields */

        $record->genericOwnerName = craft()->request->getPost('genericOwnerName', $record->genericOwnerName);
        $record->genericOwnerAlternateName = craft()->request->getPost('genericOwnerAlternateName', $record->genericOwnerAlternateName);
        $record->genericOwnerDescription = craft()->seomatic->truncateStringOnWord(craft()->request->getPost('genericOwnerDescription', $record->genericOwnerDescription), 1024);
        $record->genericOwnerUrl = craft()->request->getPost('genericOwnerUrl', $record->genericOwnerUrl);
        $record->genericOwnerTelephone = craft()->request->getPost('genericOwnerTelephone', $record->genericOwnerTelephone);
        $record->genericOwnerEmail = craft()->request->getPost('genericOwnerEmail', $record->genericOwnerEmail);
        $record->genericOwnerStreetAddress = craft()->request->getPost('genericOwnerStreetAddress', $record->genericOwnerStreetAddress);
        $record->genericOwnerAddressLocality = craft()->request->getPost('genericOwnerAddressLocality', $record->genericOwnerAddressLocality);
        $record->genericOwnerAddressRegion = craft()->request->getPost('genericOwnerAddressRegion', $record->genericOwnerAddressRegion);
        $record->genericOwnerPostalCode = craft()->request->getPost('genericOwnerPostalCode', $record->genericOwnerPostalCode);
        $record->genericOwnerAddressCountry = craft()->request->getPost('genericOwnerAddressCountry', $record->genericOwnerAddressCountry);
        $record->genericOwnerGeoLatitude = craft()->request->getPost('genericOwnerGeoLatitude', $record->genericOwnerGeoLatitude);
        $record->genericOwnerGeoLongitude = craft()->request->getPost('genericOwnerGeoLongitude', $record->genericOwnerGeoLongitude);

/* -- Corporation owner fields http://schema.org/Organization */

        $record->organizationOwnerDuns = craft()->request->getPost('organizationOwnerDuns', $record->organizationOwnerDuns);
        $record->organizationOwnerFounder = craft()->request->getPost('organizationOwnerFounder', $record->organizationOwnerFounder);
        $record->organizationOwnerFoundingDate = craft()->request->getPost('organizationOwnerFoundingDate', $record->organizationOwnerFoundingDate);
        $record->organizationOwnerFoundingLocation = craft()->request->getPost('organizationOwnerFoundingLocation', $record->organizationOwnerFoundingLocation);
        $record->organizationOwnerContactPoints = craft()->request->getPost('organizationOwnerContactPoints', array());

/* -- Person owner fields https://schema.org/Person */

        $record->personOwnerGender = craft()->request->getPost('personOwnerGender', $record->personOwnerGender);
        $record->personOwnerBirthPlace = craft()->request->getPost('personOwnerBirthPlace', $record->personOwnerBirthPlace);

/* -- LocalBusiness owner fields https://schema.org/LocalBusiness */

        $record->localBusinessPriceRange = craft()->request->getPost('localBusinessPriceRange', $record->localBusinessPriceRange);
        $hours = craft()->request->getPost('localBusinessOwnerOpeningHours', array());
        craft()->seomatic->convertTimes($hours, craft()->getTimeZone());
        $record->localBusinessOwnerOpeningHours = $hours;

/* -- Corporation owner fields http://schema.org/Corporation */

        $record->corporationOwnerTickerSymbol = craft()->request->getPost('corporationOwnerTickerSymbol', $record->corporationOwnerTickerSymbol);

/* -- Restaurant owner fields https://schema.org/Restaurant */

        $record->restaurantOwnerServesCuisine = craft()->request->getPost('restaurantOwnerServesCuisine', $record->restaurantOwnerServesCuisine);
        $record->restaurantOwnerMenuUrl = craft()->request->getPost('restaurantOwnerMenuUrl', $record->restaurantOwnerMenuUrl);
        $record->restaurantOwnerReservationsUrl = craft()->request->getPost('restaurantOwnerReservationsUrl', $record->restaurantOwnerReservationsUrl);

        $record->genericOwnerImageId = craft()->request->getPost('genericOwnerImageId', $record->genericOwnerImageId);
        $assetId = (!empty($record->genericOwnerImageId) ? $record->genericOwnerImageId[0] : null);
        $record->genericOwnerImageId = $assetId;

        if ($record->save())
        {
            craft()->userSession->setNotice(Craft::t('SEOmatic Site Identity saved.'));
            $this->redirectToPostedUrl($record);
        }
        else
        {
            craft()->userSession->setError(Craft::t('Couldn’t save SEOmatic Site Identity.'));
            $this->redirectToPostedUrl($record);
        }
    } /* -- actionSaveIdentity */

/* ================================================================================
    SOCIAL records
================================================================================ */

/* --------------------------------------------------------------------------------
    Save the Social record
-------------------------------------------------------------------------------- */

    public function actionSaveSocial()
    {
        $this->requirePostRequest();

        $locale = craft()->request->getPost('locale');
        if (!$locale)
            $locale = craft()->language;

        $record = Seomatic_SettingsRecord::model()->findByAttributes(array(
            'locale' => $locale,
            ));

        if (!$record)
        {
            throw new Exception(Craft::t('No SEOmatic Settings Record exists'));
        }

/* -- Set the Social attributes, defaulting to the existing values for whatever is missing from the post data */

        $record->twitterHandle = craft()->request->getPost('twitterHandle', $record->twitterHandle);
        $record->facebookHandle = craft()->request->getPost('facebookHandle', $record->facebookHandle);
        $record->facebookProfileId = craft()->request->getPost('facebookProfileId', $record->facebookProfileId);
        $record->facebookAppId = craft()->request->getPost('facebookAppId', $record->facebookAppId);
        $record->linkedInHandle = craft()->request->getPost('linkedInHandle', $record->linkedInHandle);
        $record->googlePlusHandle = craft()->request->getPost('googlePlusHandle', $record->googlePlusHandle);
        $record->youtubeHandle = craft()->request->getPost('youtubeHandle', $record->youtubeHandle);
        $record->youtubeChannelHandle = craft()->request->getPost('youtubeChannelHandle', $record->youtubeChannelHandle);
        $record->instagramHandle = craft()->request->getPost('instagramHandle', $record->instagramHandle);
        $record->pinterestHandle = craft()->request->getPost('pinterestHandle', $record->pinterestHandle);
        $record->githubHandle = craft()->request->getPost('githubHandle', $record->githubHandle);
        $record->vimeoHandle = craft()->request->getPost('vimeoHandle', $record->vimeoHandle);
        $record->wikipediaUrl = craft()->request->getPost('wikipediaUrl', $record->wikipediaUrl);

        if ($record->save())
        {
            craft()->userSession->setNotice(Craft::t('SEOmatic Social Media saved.'));
            $this->redirectToPostedUrl($record);
        }
        else
        {
            craft()->userSession->setError(Craft::t('Couldn’t save SEOmatic Social Media.'));
            $this->redirectToPostedUrl($record);
        }
    } /* -- actionSaveSocial */

/* ================================================================================
    CREATOR records
================================================================================ */

/* --------------------------------------------------------------------------------
    Save the Creator record
-------------------------------------------------------------------------------- */

    public function actionSaveCreator()
    {
        $this->requirePostRequest();

        $locale = craft()->request->getPost('locale');
        if (!$locale)
            $locale = craft()->language;

        $record = Seomatic_SettingsRecord::model()->findByAttributes(array(
            'locale' => $locale,
            ));

        if (!$record)
        {
            throw new Exception(Craft::t('No SEOmatic Settings record exists'));
        }

/* -- Set the Creator attributes, defaulting to the existing values for whatever is missing from the post data */

        $record->googleSiteVerification = craft()->request->getPost('googleSiteVerification', $record->googleSiteVerification);
        $record->siteCreatorType = craft()->request->getPost('siteCreatorType', $record->siteCreatorType);
        $record->siteCreatorSubType = craft()->request->getPost('siteCreatorSubType', $record->siteCreatorSubType);
        $record->siteCreatorSpecificType = craft()->request->getPost('siteCreatorSpecificType', $record->siteCreatorSpecificType);

/* -- Generic Creator fields */

        $record->genericCreatorName = craft()->request->getPost('genericCreatorName', $record->genericCreatorName);
        $record->genericCreatorAlternateName = craft()->request->getPost('genericCreatorAlternateName', $record->genericCreatorAlternateName);
        $record->genericCreatorDescription = craft()->seomatic->truncateStringOnWord(craft()->request->getPost('genericCreatorDescription', $record->genericCreatorDescription), 1024);
        $record->genericCreatorUrl = craft()->request->getPost('genericCreatorUrl', $record->genericCreatorUrl);
        $record->genericCreatorTelephone = craft()->request->getPost('genericCreatorTelephone', $record->genericCreatorTelephone);
        $record->genericCreatorEmail = craft()->request->getPost('genericCreatorEmail', $record->genericCreatorEmail);
        $record->genericCreatorStreetAddress = craft()->request->getPost('genericCreatorStreetAddress', $record->genericCreatorStreetAddress);
        $record->genericCreatorAddressLocality = craft()->request->getPost('genericCreatorAddressLocality', $record->genericCreatorAddressLocality);
        $record->genericCreatorAddressRegion = craft()->request->getPost('genericCreatorAddressRegion', $record->genericCreatorAddressRegion);
        $record->genericCreatorPostalCode = craft()->request->getPost('genericCreatorPostalCode', $record->genericCreatorPostalCode);
        $record->genericCreatorAddressCountry = craft()->request->getPost('genericCreatorAddressCountry', $record->genericCreatorAddressCountry);
        $record->genericCreatorGeoLatitude = craft()->request->getPost('genericCreatorGeoLatitude', $record->genericCreatorGeoLatitude);
        $record->genericCreatorGeoLongitude = craft()->request->getPost('genericCreatorGeoLongitude', $record->genericCreatorGeoLongitude);

/* -- Corporation Creator fields http://schema.org/Organization */

        $record->organizationCreatorDuns = craft()->request->getPost('organizationCreatorDuns', $record->organizationCreatorDuns);
        $record->organizationCreatorFounder = craft()->request->getPost('organizationCreatorFounder', $record->organizationCreatorFounder);
        $record->organizationCreatorFoundingDate = craft()->request->getPost('organizationCreatorFoundingDate', $record->organizationCreatorFoundingDate);
        $record->organizationCreatorFoundingLocation = craft()->request->getPost('organizationCreatorFoundingLocation', $record->organizationCreatorFoundingLocation);
        $record->organizationCreatorContactPoints = craft()->request->getPost('organizationCreatorContactPoints', array());

/* -- Person Creator fields https://schema.org/Person */

        $record->personCreatorGender = craft()->request->getPost('personCreatorGender', $record->personCreatorGender);
        $record->personCreatorBirthPlace = craft()->request->getPost('personCreatorBirthPlace', $record->personCreatorBirthPlace);

/* -- Corporation Creator fields http://schema.org/Corporation */

        $record->corporationCreatorTickerSymbol = craft()->request->getPost('corporationCreatorTickerSymbol', $record->corporationCreatorTickerSymbol);

/* -- Restaurant creator fields https://schema.org/Restaurant */

        $record->restaurantCreatorServesCuisine = craft()->request->getPost('restaurantCreatorServesCuisine', $record->restaurantCreatorServesCuisine);
        $record->restaurantCreatorMenuUrl = craft()->request->getPost('restaurantCreatorMenuUrl', $record->restaurantCreatorMenuUrl);
        $record->restaurantCreatorReservationsUrl = craft()->request->getPost('restaurantCreatorReservationsUrl', $record->restaurantCreatorReservationsUrl);

        $record->genericCreatorImageId = craft()->request->getPost('genericCreatorImageId', $record->genericCreatorImageId);
        $assetId = (!empty($record->genericCreatorImageId) ? $record->genericCreatorImageId[0] : null);
        $record->genericCreatorImageId = $assetId;

/* -- Humans.txt */

        $record->genericCreatorHumansTxt = craft()->request->getPost('genericCreatorHumansTxt', $record->genericCreatorHumansTxt);

        if ($record->save())
        {
            craft()->userSession->setNotice(Craft::t('SEOmatic Site Creator saved.'));
            $this->redirectToPostedUrl($record);
        }
        else
        {
            craft()->userSession->setError(Craft::t('Couldn’t save SEOmatic Site Creator.'));
            $this->redirectToPostedUrl($record);
        }
    } /* -- actionSaveCreator */

} /* -- class SeomaticController */
