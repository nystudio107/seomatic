<?php
namespace Craft;

use \crodas\TextRank\Config;
use \crodas\TextRank\TextRank;
use \crodas\TextRank\Summary;
use \crodas\TextRank\Stopword;

class SeomaticService extends BaseApplicationComponent
{

	protected $cachedSettings = array();
	protected $cachedSiteMeta = array();
	protected $cachedIdentity = array();
	protected $cachedSocial = array();
	protected $cachedCreator = array();

/* --------------------------------------------------------------------------------
    Render the all of the SEO Meta, caching it if possible
-------------------------------------------------------------------------------- */

    public function renderSiteMeta($templatePath="", $metaVars=null, $locale)
    {

/* -- Cache the results for speediness; 1 query to rule them all */

	    $shouldCache = ($metaVars != null);
		if ($shouldCache)	    
		{
			$cacheKey = 'seomatic_metacache_' . $this->getMetaHashStr($templatePath, $metaVars);
			$cache = craft()->cache->get($cacheKey);
			if ($cache)
				return $cache;
		}

/* -- If Minify is installed, minify all the things */

		if (craft()->plugins->getPlugin('Minify'))
		{
	        $htmlText = craft()->minify->htmlMin($this->render($templatePath, $metaVars));
	        $htmlText .= craft()->minify->jsMin($this->renderIdentity('', false, $metaVars, $locale));
	        $htmlText .= craft()->minify->jsMin($this->renderWebsite('', false, $metaVars, $locale));
		}
		else
		{
	        $htmlText = $this->render($templatePath, $metaVars);
	        $htmlText .= $this->renderIdentity('', false, $metaVars, $locale);
	        $htmlText .= $this->renderWebsite('', false, $metaVars, $locale);
		}
		if ($shouldCache)	    
			craft()->cache->set($cacheKey, $htmlText, null);

        return $htmlText;
    } /* -- renderSiteMeta */

/* --------------------------------------------------------------------------------
    Render the SEOmatic template
-------------------------------------------------------------------------------- */

    public function render($templatePath="", $metaVars=null, $isPreview=false)
    {

        if ($templatePath)
            {
            $htmlText = craft()->templates->render($templatePath);
            }
        else
            {
            $oldPath = craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            craft()->path->setTemplatesPath($newPath);

/* -- Render the core template */

            $templateName = '_seo_meta';
            if ($isPreview)
                $templateName = $templateName . 'Preview';
            if ($metaVars)
                $htmlText = craft()->templates->render($templateName, $metaVars);
            else
                $htmlText = craft()->templates->render($templateName);

            craft()->path->setTemplatesPath($oldPath);
            }

        return $htmlText;
    } /* -- render */

/* --------------------------------------------------------------------------------
    Render the SEOmatic display preview template
-------------------------------------------------------------------------------- */

    public function renderDisplayPreview($templateName="", $metaVars)
    {
        $oldPath = craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'seomatic/templates';
        craft()->path->setTemplatesPath($newPath);

/* -- Render the SEOmatic display preview template */

        $htmlText = craft()->templates->render($templateName, $metaVars);

        craft()->path->setTemplatesPath($oldPath);

        return $htmlText;
    } /* -- renderDisplayPreview */

/* --------------------------------------------------------------------------------
    Render the SEOmatic Identity template
-------------------------------------------------------------------------------- */

    public function renderIdentity($templatePath="", $isPreview=false, $metaVars, $locale)
    {
        if ($templatePath)
            {
            $htmlText = craft()->templates->render($templatePath);
            }
        else
            {
            $oldPath = craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            craft()->path->setTemplatesPath($newPath);

/* -- Render the Site Identity JSON-LD */

            $identity = $this->getIdentity($locale);
            $templateName = 'json-ld/_' . 'identity';
            if ($isPreview)
                $templateName = $templateName . 'Preview';
            $htmlText = craft()->templates->render($templateName, $metaVars);

            craft()->path->setTemplatesPath($oldPath);
            }
        return $htmlText;
    } /* -- renderIdentity */

/* --------------------------------------------------------------------------------
    Render the SEOmatic WebSite template
-------------------------------------------------------------------------------- */

    public function renderWebsite($templatePath="", $isPreview=false, $metaVars, $locale)
    {
        if ($templatePath)
            {
            $htmlText = craft()->templates->render($templatePath);
            }
        else
            {
            $oldPath = craft()->path->getTemplatesPath();
            $newPath = craft()->path->getPluginsPath().'seomatic/templates';
            craft()->path->setTemplatesPath($newPath);

/* -- Render the WebSite JSON-LD */

            $identity = $this->getIdentity($locale);
            $templateName = 'json-ld/_' . 'website';
            if ($isPreview)
                $templateName = $templateName . 'Preview';
            $htmlText = craft()->templates->render($templateName, $metaVars);

            craft()->path->setTemplatesPath($oldPath);
            }
        return $htmlText;
    } /* -- renderWebsite */

/* --------------------------------------------------------------------------------
    Render the SEOmatic globals
-------------------------------------------------------------------------------- */

    public function renderGlobals($metaVars, $forTemplate="")
    {
        $htmlText = "";
        
        foreach ($metaVars as $key => $value)
        {
            if (is_array($value))
            {
                $line = "{% set " . $key . " = { " . "\n";
                foreach ($value as $arrayKey => $arrayValue)
                {
                    if (is_string($arrayValue))
                        $line = $line . "    " . $arrayKey . ": '" . htmlspecialchars($arrayValue) . "'," . "\n";
                    else
                        $line = $line . "    " . $arrayKey . ": {" . $arrayValue . "}," . "\n";
                }
                $line = $line . "} %}" . "\n";
            }
            else
            {
                if (is_string($value))
                    $line = "{% set " . $key . " = '" . htmlspecialchars($value) . "' %}" . "\n";
                else
                    $line = "{% set " . $key . " = {" . $value . "} %}" . "\n";
            }
            $htmlText = $htmlText . $line . "\n";
        }
        
        return $htmlText;
    } /* -- renderGlobals */

/* --------------------------------------------------------------------------------
    Get the seomatic globals
-------------------------------------------------------------------------------- */

    public function getGlobals($forTemplate="", $locale)
    {
	    if (!$locale)
			$locale = craft()->language;
		
/* -- Load in our globals */

        $meta = $this->getMeta($forTemplate);
        $siteMeta = $this->getSiteMeta($locale);
        $identity = $this->getIdentity($locale);
        $social = $this->getSocial($locale);
        $creator = $this->getCreator($locale);

/* -- Swap in the seoImageId for the actual asset */
        
        if (isset($meta['seoImageId']))
        {
	        $meta['seoImage'] = craft()->assets->getFileById($meta['seoImageId']);
	        unset($meta['seoImageId']);
        }

/* -- Get a full qualified URL for the current request */

        $siteUrl = craft()->getSiteUrl();
        $requestUrl = craft()->request->url;
        if (($siteUrl[strlen($siteUrl) -1] == '/') && ($requestUrl[0] == '/'))
        {
            $siteUrl = rtrim($siteUrl, '/');
        }
        $fullUrl = $siteUrl . $requestUrl;
        
        $meta['canonicalUrl'] = $fullUrl;

/* -- Merge the meta with the global meta */

		$globalMeta['seoTitle'] = $siteMeta['siteSeoTitle'];
		$globalMeta['seoDescription'] = $siteMeta['siteSeoDescription'];
		$globalMeta['seoKeywords'] = $siteMeta['siteSeoKeywords'];
        $globalMeta['seoImage'] = craft()->assets->getFileById($siteMeta['siteSeoImageId']);
        $meta = array_merge($globalMeta, $meta);

/* -- Set some useful runtime variables, too */

        $runtimeVars = array(
            'seomaticTemplatePath' => '',
        );

/* -- Swap in the seoImageId for the actual asset */
        
        $siteMeta['siteSeoImage'] = craft()->assets->getFileById($siteMeta['siteSeoImageId']);
        unset($siteMeta['siteSeoImageId']);

/* -- Swap in the genericOwnerImageId for the actual asset */
        
        $identity['genericOwnerImage'] = craft()->assets->getFileById($identity['genericOwnerImageId']);
        unset($identity['genericOwnerImageId']);

/* -- Swap in the genericCreatorImageId for the actual asset */
        
        $creator['genericCreatorImage'] = craft()->assets->getFileById($creator['genericCreatorImageId']);
        unset($creator['genericCreatorImageId']);

/* -- Add in the social media URLs */

		$this->addSocialUrls($social, $identity['siteOwnerType']);
				
/* -- Return our global variables */

        $result = array('seomaticMeta' => $meta,
                        'seomaticSiteMeta' => $siteMeta,
                        'seomaticSocial' => $social,
                        'seomaticIdentity' => $identity,
                        'seomaticCreator' => $creator,
                        );
        $result = array_merge($result, $runtimeVars);
        
        $this->sanitizeMetaVars($result);
        
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
					$baseSettings = $this->cachedSettings[$baseLocale];
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
				}
		        $baseResult = $baseSettings->attributes;
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
		$siteMeta['siteSeoDescription'] = $settings['siteSeoDescription'];
		$siteMeta['siteSeoKeywords'] = $settings['siteSeoKeywords'];
		$siteMeta['siteSeoImageId'] = $settings['siteSeoImageId'];

        $result = $siteMeta;
        
        $this->cachedSiteMeta[$locale] = $result;
        return $result;
    } /* -- getSiteMeta */

/* --------------------------------------------------------------------------------
    Get the identity record
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
		$identity['siteOwnerType'] = $settings['siteOwnerType'];
		
		$identity['genericOwnerName'] = $settings['genericOwnerName'];
		$identity['genericOwnerAlternateName'] = $settings['genericOwnerAlternateName'];
		$identity['genericOwnerDescription'] = $settings['genericOwnerDescription'];
		$identity['genericOwnerUrl'] = $settings['genericOwnerUrl'];
		$identity['genericOwnerImageId'] = $settings['genericOwnerImageId'];
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

		$identity['personOwnerGender'] = $settings['personOwnerGender'];
		$identity['personOwnerBirthPlace'] = $settings['personOwnerBirthPlace'];

		$identity['corporationOwnerTickerSymbol'] = $settings['corporationOwnerTickerSymbol'];

		$identity['restaurantOwnerServesCuisine'] = $settings['restaurantOwnerServesCuisine'];

/* -- Computed identity strings */

		$now = new DateTime;
		$identity['copyrightNotice'] = Craft::t("Copyright") . " &copy;" . $now->year() . ", " . $identity['genericOwnerName'] . ". " . Craft::t("All rights reserved.");

		$identity['addressString'] = '';
		$identity['addressHtml'] = '';
		$identity['mapUrl'] = '';
		if ($identity['genericOwnerStreetAddress'] &&
			$identity['genericOwnerAddressLocality'] &&
			$identity['genericOwnerAddressRegion'] &&
			$identity['genericOwnerPostalCode'])
		{
			$identity['addressString'] = $identity['genericOwnerName'] . ", "
										. $identity['genericOwnerStreetAddress'] . ", "
										. $identity['genericOwnerAddressLocality'] . ", "
										. $identity['genericOwnerAddressRegion'] . " "
										. $identity['genericOwnerPostalCode'] . ", "
										. $identity['genericOwnerAddressCountry'];
										
			$identity['addressHtml'] = $identity['genericOwnerName'] . "<br />"
										. $identity['genericOwnerStreetAddress'] . "<br />"
										. $identity['genericOwnerAddressLocality'] . ", " . $identity['genericOwnerAddressRegion'] . " " . $identity['genericOwnerPostalCode'] . "<br />"
										. $identity['genericOwnerAddressCountry'] . "<br />";
			
			$params=array();
			$params = count($params) ? '&' . http_build_query($params) : '';
			$query = urlencode($identity['addressString']);
			$identity['mapUrl'] = "http://maps.google.com/maps?q={$query}{$params}";
		}
		
        $result = $identity;
        
        $this->cachedIdentity[$locale] = $result;
        
        return $result;
    } /* -- getIdentity */

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
		$social['linkedInHandle'] = $settings['linkedInHandle'];
		$social['googlePlusHandle'] = $settings['googlePlusHandle'];
		$social['youtubeHandle'] = $settings['youtubeHandle'];
		$social['instagramHandle'] = $settings['instagramHandle'];
		$social['pinterestHandle'] = $settings['pinterestHandle'];

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
		
		$creator['googleSiteVerification'] = $settings['googleSiteVerification'];
		$creator['siteCreatorType'] = $settings['siteCreatorType'];
		
		$creator['genericCreatorName'] = $settings['genericCreatorName'];
		$creator['genericCreatorAlternateName'] = $settings['genericCreatorAlternateName'];
		$creator['genericCreatorDescription'] = $settings['genericCreatorDescription'];
		$creator['genericCreatorUrl'] = $settings['genericCreatorUrl'];
		$creator['genericCreatorImageId'] = $settings['genericCreatorImageId'];
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

		$creator['personCreatorGender'] = $settings['personCreatorGender'];
		$creator['personCreatorBirthPlace'] = $settings['personCreatorBirthPlace'];

		$creator['corporationCreatorTickerSymbol'] = $settings['corporationCreatorTickerSymbol'];

/* -- Computed identity strings */

		$now = new DateTime;
		$creator['copyrightNotice'] = Craft::t("Copyright") . " &copy;" . $now->year() . ", " . $creator['genericCreatorName'] . ". " . Craft::t("All rights reserved.");

		$creator['addressString'] = '';
		$creator['addressHtml'] = '';
		$creator['mapUrl'] = '';
		if ($creator['genericCreatorStreetAddress'] &&
			$creator['genericCreatorAddressLocality'] &&
			$creator['genericCreatorAddressRegion'] &&
			$creator['genericCreatorPostalCode'])
		{
			$creator['addressString'] = $creator['genericCreatorName'] . ", "
										. $creator['genericCreatorStreetAddress'] . ", "
										. $creator['genericCreatorAddressLocality'] . ", "
										. $creator['genericCreatorAddressRegion'] . " "
										. $creator['genericCreatorPostalCode'] . ", "
										. $creator['genericCreatorAddressCountry'];
										
			$creator['addressHtml'] = $creator['genericCreatorName'] . "<br />"
										. $creator['genericCreatorStreetAddress'] . "<br />"
										. $creator['genericCreatorAddressLocality'] . ", " . $creator['genericCreatorAddressRegion'] . " " . $creator['genericCreatorPostalCode'] . "<br />"
										. $creator['genericCreatorAddressCountry'] . "<br />";
	
			$params=array();
			$params = count($params) ? '&' . http_build_query($params) : '';
			$query = urlencode($creator['addressString']);
			$creator['mapUrl'] = "http://maps.google.com/maps?q={$query}{$params}";
		}
        $result = $creator;
        
        $this->cachedCreator[$locale] = $result;
        
        return $result;
    } /* -- getCreator */

/* --------------------------------------------------------------------------------
    Get the meta record
-------------------------------------------------------------------------------- */

    public function getMeta($forTemplate="")
    {
        $result = array();

        if ($forTemplate)
        {
            $whereQuery = '`metaPath` = ' . '\'' .$forTemplate . '\'';
            $metaRecord = Seomatic_MetaRecord::model()->find($whereQuery);
            if ($metaRecord)
            {
                $meta = $metaRecord->attributes;
                $meta = array_filter($meta);
                $result = array_merge($result, $meta);
            }
            
        }

/* -- Get rid of properties we don't care about */

        if ($result)
        {
            unset($result['metaType']);
            unset($result['metaPath']);
            unset($result['id']);
            unset($result['dateCreated']);
            unset($result['dateUpdated']);
            unset($result['uid']);
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
    Add the social media URLs
-------------------------------------------------------------------------------- */

	private function addSocialUrls(&$social, $siteOwnerType)
	{
		if ($social['twitterHandle'])
		{
			ltrim($social['twitterHandle'], '@');
			$social['twitterUrl'] = "https://twitter.com/" . $social['twitterHandle'];
		}
		else
			$social['twitterUrl'] = '';
			
		if ($social['facebookHandle'])
		{
			$social['facebookUrl'] = "https://www.facebook.com/" . $social['facebookHandle'];
		}
		else
			$social['facebookUrl'] = '';

		if ($social['googlePlusHandle'])
		{
			ltrim($social['googlePlusHandle'], '+');
			$social['googlePlusUrl'] = "https://plus.google.com/+" . $social['googlePlusHandle'];
		}
		else
			$social['googlePlusUrl'] = '';

		if ($social['linkedInHandle'])
		{
			if ($siteOwnerType == "person")
				$social['linkedInUrl'] = "https://www.linkedin.com/in/" . $social['linkedInHandle'];
			else
				$social['linkedInUrl'] = "https://www.linkedin.com/company/" . $social['linkedInHandle'];
		}
		else
			$social['linkedInUrl'] = '';

		if ($social['youtubeHandle'])
		{
			$social['youtubeUrl'] = "https://www.youtube.com/user/" . $social['youtubeHandle'];
		}
		else
			$social['youtubeUrl'] = '';

		if ($social['instagramHandle'])
		{
			$social['instagramUrl'] = "https://www.instagram.com/" . $social['instagramHandle'];
		}
		else
			$social['instagramUrl'] = '';

		if ($social['pinterestHandle'])
		{
			$social['pinterestUrl'] = "https://www.pinterest.com/" . $social['pinterestHandle'];
		}
		else
			$social['pinterestUrl'] = '';
	} /* -- addSocialUrls */
	
/* --------------------------------------------------------------------------------
    Get a md5 hash string for this combination of $metaVars
-------------------------------------------------------------------------------- */

	private function getMetaHashStr($templatePath, $metaVars)
	{
		$hashStr = $templatePath;
        foreach ($metaVars as $key => $value)
        {
            if (is_array($value))
            {
                
                foreach ($value as $arrayKey => $arrayValue)
                {
	                $hashStr = $hashStr . (String)$arrayValue;
                }
            }
            else
            {
	            $hashStr = $hashStr . (String)$value;
            }
        }
		
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

/* -- Truncate seoTitle, seoDescription, and seoKeywords to recommended values */

        $vars = array('seoTitle' => (70 - strlen(" | ") - strlen($seomaticSiteMeta['siteSeoName'])), 'seoDescription' => 160, 'seoKeywords' => 200);
        
        foreach ($vars as $key => $value)
        {
            if (isset($seomaticMeta[$key]))
            {
                $seomaticMeta[$key] = $this->truncateStringOnWord($seomaticMeta[$key], $value);
            }
        }

/* -- Make sure all of our variables are properly encoded */

        foreach ($seomaticMeta as $key => $value)
        {
            if (is_string($value))
            {
				$seomaticMeta[$key] = craft()->config->parseEnvironmentString($value);
				$seomaticMeta[$key] = strip_tags($value);
                $seomaticMeta[$key] = htmlspecialchars($value);
            }
        }

        foreach ($seomaticSiteMeta as $key => $value)
        {
            if (is_string($value))
            {
				$seomaticSiteMeta[$key] = craft()->config->parseEnvironmentString($value);
				$seomaticSiteMeta[$key] = strip_tags($value);
                $seomaticSiteMeta[$key] = htmlspecialchars($value);
            }
        }

        foreach ($seomaticIdentity as $key => $value)
        {
            if (is_string($value))
            {
				$seomaticIdentity[$key] = craft()->config->parseEnvironmentString($value);
				if (($key != 'addressHtml') && ($key !='copyrightNotice'))
				{
					$seomaticIdentity[$key] = strip_tags($value);
					if ($key == 'genericOwnerEmail')
						$seomaticIdentity[$key] = $this->encodeEmailAddress($value);
					else
	                	$seomaticIdentity[$key] = htmlspecialchars($value);
                }
            }
        }

        foreach ($seomaticSocial as $key => $value)
        {
            if (is_string($value))
            {
				$seomaticSocial[$key] = craft()->config->parseEnvironmentString($value);
				$seomaticSocial[$key] = strip_tags($value);
                $seomaticSocial[$key] = htmlspecialchars($value);
            }
        }

        foreach ($seomaticCreator as $key => $value)
        {
            if (is_string($value))
            {
				$seomaticCreator[$key] = craft()->config->parseEnvironmentString($value);
				if (($key != 'addressHtml') && ($key !='copyrightNotice'))
				{
					$seomaticCreator[$key] = strip_tags($value);
					if ($key == 'genericCreatorEmail')
						$seomaticCreator[$key] = $this->encodeEmailAddress($value);
					else
	                	$seomaticCreator[$key] = htmlspecialchars($value);
                }
            }
        }

        $metaVars['seomaticMeta'] = $seomaticMeta;
        $metaVars['seomaticSiteMeta'] = $seomaticSiteMeta;
        $metaVars['seomaticIdentity'] = $seomaticIdentity;
        $metaVars['seomaticSocial'] = $seomaticSocial;
        $metaVars['seomaticCreator'] = $seomaticCreator;

    } /* -- sanitizeMetaVars */

/* --------------------------------------------------------------------------------
    Extract the most important words from the passed in text via TextRank
-------------------------------------------------------------------------------- */

    public function extractKeywords($text = null, $limit = 15, $withoutStopWords = true)
    {
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

        return (is_array($keywords)) ? implode(", ", array_slice(array_keys($keywords), 0, $limit)) : $keywords;
    } /* -- extractKeywords */

/* --------------------------------------------------------------------------------
    Extract a summary from the text, or if it's not long enough, just return the text
-------------------------------------------------------------------------------- */

    public function extractSummary($text = null, $limit = null, $withoutStopWords = true)
    {
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

        return $summary;
    } /* -- extractSummary */

/* --------------------------------------------------------------------------------
    Cleanup text before extracting keywords/summary
-------------------------------------------------------------------------------- */

    private function _cleanupText($text = null)
    {
/* -- convert to UTF-8 */

        $text = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
        
/* -- strip HTML tags */

        $text = preg_replace('#<[^>]+>#', ' ', $text);
        
/* -- remove excess whitespace */

        $text = preg_replace('/\s+/', ' ', $text);

        return $text;
    } /* -- _cleanupText */

/* --------------------------------------------------------------------------------
    Truncate the the string passed in, breaking it on a word.  $desiredLength
    is in characters; the returned string will be broken on a whole-word
    boundary, with an … appended to the end if it is truncated
-------------------------------------------------------------------------------- */

    public function truncateStringOnWord($theString, $desiredLength)
    {
        if (strlen($theString) > $desiredLength) 
        {
            $theString = wordwrap($theString, $desiredLength);
            $theString = substr($theString, 0, strpos($theString, "\n"));
            $theString = $theString . "…";
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

} /* -- class SeomaticService */
