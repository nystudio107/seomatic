<?php
namespace Craft;

class Seomatic_SettingsRecord extends BaseRecord
{

    public function getTableName()
    {
        return 'seomatic_settings';
    }

    protected function defineAttributes()
    {
        return array(
            'locale'                        	=> array(AttributeType::String, 'default' => ''),

/* --------------------------------------------------------------------------------
	SITEMETA settings
-------------------------------------------------------------------------------- */

            'siteSeoName'                   	=> array(AttributeType::String, 'default' => ''),
            'siteSeoTitle'                  	=> array(AttributeType::String, 'default' => ''),
            'siteSeoTitleSeparator'             => array(AttributeType::String, 'maxLength' => 10, 'default' => '|'),
            'siteSeoTitlePlacement'             => array(AttributeType::Enum, 'values' => "before,after,none", 'default' => 'after'),
            'siteSeoDescription'            	=> array(AttributeType::String, 'default' => ''),
            'siteSeoKeywords'               	=> array(AttributeType::String, 'default' => ''),
            'siteSeoImageTransform'             => array(AttributeType::String, 'maxLength' => 100, 'default' => ''),
            'siteSeoFacebookImageTransform'     => array(AttributeType::String, 'maxLength' => 100, 'default' => ''),
            'siteSeoTwitterImageTransform'      => array(AttributeType::String, 'maxLength' => 100, 'default' => ''),
            'siteTwitterCardType'				=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'siteOpenGraphType'					=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'siteRobots'						=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'siteRobotsTxt'                     => array(AttributeType::String, 'column' => ColumnType::Text),
            'siteLinksSearchTargets'            => array(AttributeType::Mixed),
            'siteLinksQueryInput'               => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),

/* --------------------------------------------------------------------------------
	IDENTITY settings
-------------------------------------------------------------------------------- */

            'googleSiteVerification'            => array(AttributeType::String, 'maxLength' => 100, 'default' => ''),
            'bingSiteVerification'              => array(AttributeType::String, 'maxLength' => 100, 'default' => ''),
            'googleAnalyticsUID'                => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'googleTagManagerID'                => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'googleAnalyticsSendPageview'       => array(AttributeType::Bool, 'default' => true),
            'googleAnalyticsAdvertising'        => array(AttributeType::Bool, 'default' => false),
            'googleAnalyticsEcommerce'          => array(AttributeType::Bool, 'default' => false),
            'googleAnalyticsEEcommerce'         => array(AttributeType::Bool, 'default' => false),
            'googleAnalyticsLinkAttribution'    => array(AttributeType::Bool, 'default' => false),
            'googleAnalyticsLinker'             => array(AttributeType::Bool, 'default' => false),
            'siteOwnerType'                     => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'siteOwnerSubType'                  => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'siteOwnerSpecificType'             => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),

/* -- Generic owner fields */

            'genericOwnerName'					=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAlternateName'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerDescription'			=> array(AttributeType::String, 'maxLength' => 1024, 'default' => ''),
            'genericOwnerUrl'					=> array(AttributeType::String, 'default' => ''),
            'genericOwnerTelephone'				=> array(AttributeType::String, 'default' => ''),
            'genericOwnerEmail'					=> array(AttributeType::String, 'default' => ''),
            'genericOwnerStreetAddress'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAddressLocality'		=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAddressRegion'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerPostalCode'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAddressCountry'		=> array(AttributeType::String, 'default' => ''),
            'genericOwnerGeoLatitude'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerGeoLongitude'			=> array(AttributeType::String, 'default' => ''),

/* -- Generic organization owner fields http://schema.org/Organization */

            'organizationOwnerDuns'				=> array(AttributeType::String, 'default' => ''),
            'organizationOwnerFounder'			=> array(AttributeType::String, 'default' => ''),
            'organizationOwnerFoundingDate'		=> array(AttributeType::String, 'default' => ''),
            'organizationOwnerFoundingLocation' => array(AttributeType::String, 'default' => ''),
            'organizationOwnerContactPoints'    => array(AttributeType::Mixed),

/* -- LocalBusiness owner fields https://schema.org/LocalBusiness */

            'localBusinessPriceRange'           => array(AttributeType::String, 'maxLength' => 10, 'default' => '$$$'),
            'localBusinessOwnerOpeningHours'    => array(AttributeType::Mixed),

/* -- Corporation owner fields http://schema.org/Corporation */

            'corporationOwnerTickerSymbol'		=> array(AttributeType::String, 'default' => ''),

/* -- Restaurant owner fields https://schema.org/Restaurant */

            'restaurantOwnerServesCuisine'		=> array(AttributeType::String, 'default' => ''),
            'restaurantOwnerMenuUrl'            => array(AttributeType::String, 'default' => ''),
            'restaurantOwnerReservationsUrl'    => array(AttributeType::String, 'default' => ''),

/* -- Person owner fields https://schema.org/Person */

            'personOwnerGender'					=> array(AttributeType::String, 'default' => ''),
            'personOwnerBirthPlace'				=> array(AttributeType::String, 'default' => ''),

/* -- This is defined in definteRelations() below, of note:
      You don’t need to specify the foreign key column name in BELONGS_TO relations (defaults to the relation name appended with “Id”)
      https://craftcms.com/docs/plugins/records
            'genericOwnerImage'                  => array(AttributeType::String, 'default' => 0),
*/

/* --------------------------------------------------------------------------------
	SOCIAL settings
-------------------------------------------------------------------------------- */

            'twitterHandle'             		=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'facebookHandle'            		=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'facebookProfileId'                 => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'facebookAppId'                     => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'linkedInHandle'            		=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'googlePlusHandle'          		=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'youtubeHandle'                     => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'youtubeChannelHandle'              => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'instagramHandle'					=> array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'pinterestHandle'                   => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'githubHandle'                      => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),
            'vimeoHandle'                       => array(AttributeType::String, 'maxLength' => 50, 'default' => ''),


/* --------------------------------------------------------------------------------
	CREATOR settings
-------------------------------------------------------------------------------- */

            'siteCreatorType'                 	=> array(AttributeType::String, 'default' => ''),
            'siteCreatorSubType'                => array(AttributeType::String, 'default' => ''),
            'siteCreatorSpecificType'           => array(AttributeType::String, 'default' => ''),

/* -- Generic Creator fields */

            'genericCreatorName'				=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAlternateName'		=> array(AttributeType::String, 'default' => ''),
            'genericCreatorDescription'			=> array(AttributeType::String, 'maxLength' => 1024, 'default' => ''),
            'genericCreatorUrl'					=> array(AttributeType::String, 'default' => ''),
            'genericCreatorTelephone'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorEmail'				=> array(AttributeType::String, 'default' => ''),
            'genericCreatorStreetAddress'		=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAddressLocality'		=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAddressRegion'		=> array(AttributeType::String, 'default' => ''),
            'genericCreatorPostalCode'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAddressCountry'		=> array(AttributeType::String, 'default' => ''),
            'genericCreatorGeoLatitude'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorGeoLongitude'		=> array(AttributeType::String, 'default' => ''),

/* -- Generic organization Creator fields http://schema.org/Organization */

            'organizationCreatorDuns'			=> array(AttributeType::String, 'default' => ''),
            'organizationCreatorFounder'		=> array(AttributeType::String, 'default' => ''),
            'organizationCreatorFoundingDate'	=> array(AttributeType::String, 'default' => ''),
            'organizationCreatorFoundingLocation'   => array(AttributeType::String, 'default' => ''),
            'organizationCreatorContactPoints'  => array(AttributeType::Mixed),

/* -- LocalBusiness Creator fields https://schema.org/LocalBusiness */

            'localBusinessCreatorOpeningHours'  => array(AttributeType::Mixed),

/* -- Corporation Creator fields http://schema.org/Corporation */

            'corporationCreatorTickerSymbol'	=> array(AttributeType::String, 'default' => ''),

/* -- Restaurant Creator fields https://schema.org/Restaurant */

            'restaurantCreatorServesCuisine'    => array(AttributeType::String, 'default' => ''),
            'restaurantCreatorMenuUrl'          => array(AttributeType::String, 'default' => ''),
            'restaurantCreatorReservationsUrl'  => array(AttributeType::String, 'default' => ''),

/* -- Person Creator fields https://schema.org/Person */

            'personCreatorGender'				=> array(AttributeType::String, 'default' => ''),
            'personCreatorBirthPlace'			=> array(AttributeType::String, 'default' => ''),

/* -- Humans.txt */

            'genericCreatorHumansTxt'           => array(AttributeType::String, 'column' => ColumnType::Text),

/* -- This is defined in definteRelations() below, of note:
      You don’t need to specify the foreign key column name in BELONGS_TO relations (defaults to the relation name appended with “Id”)
      https://craftcms.com/docs/plugins/records
            'genericCreatorImage'                  => array(AttributeType::String, 'default' => 0),
*/
        );
    }

/* --------------------------------------------------------------------------------
	Get the default Humans.txt template
-------------------------------------------------------------------------------- */

    public function getDefaultHumans()
    {
        $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'seomatic/templates';
        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

/* -- Return the Humans.txt default template */

        $templateName = '_humansDefault';
		$loader = new TemplateLoader;
        $template = $loader->getSource($templateName);

        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);

        return $template;
    } /* -- _getDefaultHumans */

/* --------------------------------------------------------------------------------
    Get the default robots.txt template
-------------------------------------------------------------------------------- */

    public function getDefaultRobots()
    {
        $oldPath = method_exists(craft()->templates, 'getTemplatesPath') ? craft()->templates->getTemplatesPath() : craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'seomatic/templates';
        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($newPath) : craft()->path->setTemplatesPath($newPath);

/* -- Return the robots.txt default template */

        $templateName = '_robotsDefault';
        $loader = new TemplateLoader;
        $template = $loader->getSource($templateName);

        method_exists(craft()->templates, 'setTemplatesPath') ? craft()->templates->setTemplatesPath($oldPath) : craft()->path->setTemplatesPath($oldPath);

        return $template;
    } /* -- _getDefaultRobots */

/* --------------------------------------------------------------------------------
	Relationships
-------------------------------------------------------------------------------- */

    public function defineRelations()
    {
        return array(
            'siteSeoImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
            'siteSeoTwitterImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
            'siteSeoFacebookImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
            'genericOwnerImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
            'genericCreatorImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
        );
    }

} /* -- class Seomatic_SettingsRecord */