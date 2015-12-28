<?php
namespace Craft;

class Seomatic_SocialModel extends BaseModel
{
    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'locale'                    => array(AttributeType::String, 'default' => ''),
            'twitterHandle'             => array(AttributeType::String, 'default' => ''),
            'facebookHandle'            => array(AttributeType::String, 'default' => ''),
            'facebookProfileId'         => array(AttributeType::String, 'default' => ''),
            'linkedInHandle'            => array(AttributeType::String, 'default' => ''),
            'googlePlusHandle'          => array(AttributeType::String, 'default' => ''),
            'youtubeHandle'				=> array(AttributeType::String, 'default' => ''),
            'instagramHandle'			=> array(AttributeType::String, 'default' => ''),
            'pinterestHandle'			=> array(AttributeType::String, 'default' => ''),
        ));
    }

} /* -- class Seomatic_SocialModel */