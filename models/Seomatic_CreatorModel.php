<?php
namespace Craft;

class Seomatic_CreatorModel extends BaseModel
{
    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'locale'                        	=> array(AttributeType::String, 'default' => ''),
            'siteCreatorType'                   => array(AttributeType::String, 'default' => 'Organization'),
            'siteCreatorSubType'                => array(AttributeType::String, 'default' => 'Corporation'),
            'siteCreatorSpecificType'           => array(AttributeType::String, 'default' => ''),

/* -- Generic Creator fields */

            'genericCreatorName'					=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAlternateName'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorDescription'				=> array(AttributeType::String, 'default' => ''),
            'genericCreatorUrl'						=> array(AttributeType::String, 'default' => ''),
            'genericCreatorImageId'					=> array(AttributeType::String, 'default' => ''),
            'genericCreatorTelephone'				=> array(AttributeType::String, 'default' => ''),
            'genericCreatorEmail'					=> array(AttributeType::String, 'default' => ''),
            'genericCreatorStreetAddress'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAddressLocality'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAddressRegion'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorPostalCode'				=> array(AttributeType::String, 'default' => ''),
            'genericCreatorAddressCountry'			=> array(AttributeType::String, 'default' => ''),
            'genericCreatorGeoLatitude'				=> array(AttributeType::String, 'default' => ''),
            'genericCreatorGeoLongitude'			=> array(AttributeType::String, 'default' => ''),

/* -- Corporation Creator fields http://schema.org/Organization */

            'organizationCreatorDuns'				=> array(AttributeType::String, 'default' => ''),
            'organizationCreatorFounder'			=> array(AttributeType::String, 'default' => ''),
            'organizationCreatorFoundingDate'		=> array(AttributeType::String, 'default' => ''),
            'organizationCreatorFoundingLocation'	=> array(AttributeType::String, 'default' => ''),

/* -- Person Creator fields https://schema.org/Person */

            'personCreatorGender'					=> array(AttributeType::String, 'default' => ''),
            'personCreatorBirthPlace'				=> array(AttributeType::String, 'default' => ''),

/* -- Corporation Creator fields http://schema.org/Corporation */

            'corporationCreatorTickerSymbol'		=> array(AttributeType::String, 'default' => ''),
        ));
    }

} /* -- class Seomatic_CreatorModel */