<?php
namespace Craft;

class Seomatic_SiteMetaModel extends BaseModel
{
    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'locale'                        	=> array(AttributeType::String, 'default' => ''),
            'siteSeoName'                   	=> array(AttributeType::String, 'default' => ''),
            'siteSeoTitle'                  	=> array(AttributeType::String, 'default' => ''),
            'siteSeoDescription'            	=> array(AttributeType::String, 'default' => ''),
            'siteSeoKeywords'               	=> array(AttributeType::String, 'default' => ''),
            'siteSeoImageId'                	=> array(AttributeType::Number, 'default' => null),
        ));
    }

} /* -- class Seomatic_SiteMetaModel */