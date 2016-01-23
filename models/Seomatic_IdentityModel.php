<?php
namespace Craft;

class Seomatic_IdentityModel extends BaseModel
{
    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'locale'                        	=> array(AttributeType::String, 'default' => ''),
			'googleSiteVerification'    		=> array(AttributeType::String, 'default' => ''),
            'siteOwnerType'                     => array(AttributeType::String, 'default' => 'Organization'),
            'siteOwnerSubType'                  => array(AttributeType::String, 'default' => 'Corporation'),
            'siteOwnerSpecificType'             => array(AttributeType::String, 'default' => ''),

/* -- Generic owner fields */

            'genericOwnerName'					=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAlternateName'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerDescription'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerUrl'					=> array(AttributeType::String, 'default' => ''),
            'genericOwnerImageId'				=> array(AttributeType::String, 'default' => ''),
            'genericOwnerTelephone'				=> array(AttributeType::String, 'default' => ''),
            'genericOwnerEmail'					=> array(AttributeType::String, 'default' => ''),
            'genericOwnerStreetAddress'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAddressLocality'		=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAddressRegion'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerPostalCode'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerAddressCountry'		=> array(AttributeType::String, 'default' => ''),
            'genericOwnerGeoLatitude'			=> array(AttributeType::String, 'default' => ''),
            'genericOwnerGeoLongitude'			=> array(AttributeType::String, 'default' => ''),

/* -- Corporation owner fields http://schema.org/Organization */

            'organizationOwnerDuns'				=> array(AttributeType::String, 'default' => ''),
            'organizationOwnerFounder'			=> array(AttributeType::String, 'default' => ''),
            'organizationOwnerFoundingDate'		=> array(AttributeType::String, 'default' => ''),
            'organizationOwnerFoundingLocation'	=> array(AttributeType::String, 'default' => ''),

/* -- Person owner fields https://schema.org/Person */

            'personOwnerGender'					=> array(AttributeType::String, 'default' => ''),
            'personOwnerBirthPlace'				=> array(AttributeType::String, 'default' => ''),

/* -- Corporation owner fields http://schema.org/Corporation */

            'corporationOwnerTickerSymbol'		=> array(AttributeType::String, 'default' => ''),

/* -- Restaurant owner fields https://schema.org/Restaurant */

            'restaurantOwnerServesCuisine'		=> array(AttributeType::String, 'default' => ''),
        ));
    }

} /* -- class Seomatic_IdentityModel */