<?php
namespace Craft;

class Seomatic_MetaModel extends BaseElementModel
{
    protected $elementType = 'Seomatic_Meta';

    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'locale'			=> array(AttributeType::String, 'default' => craft()->language),
            'elementId'			=> array(AttributeType::Number, 'default' => 0),
            'metaType'          => array(AttributeType::Enum, 'values' => "default,template", 'default' => 'template'),
            'metaPath'          => array(AttributeType::String, 'default' => ''),
            'seoTitle'          => array(AttributeType::String, 'default' => 'This is the title of this template page.'),
            'seoDescription'    => array(AttributeType::String, 'default' => 'This is a natural language description of the content on this template page.'),
            'seoKeywords'       => array(AttributeType::String, 'default' => 'This is a list of comma-separated key words that are relevant to the content on this template page.'),
            'seoImageId'        => array(AttributeType::Number, 'default' => 0),
        ));
    }

/*
	public function getContentTable()
	{
	     return 'seomatic_meta';
	}
*/	
    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        $locale = (craft()->isLocalized() && $this->locale != craft()->language) ? '/' . $this->locale : '';
        return UrlHelper::getCpUrl('seomatic/' . 'meta' . '/' . $this->id . $locale);
    }

} /* -- class Seomatic_MetaModel */