<?php
namespace Craft;

class Seomatic_MetaFieldModel extends Seomatic_MetaModel
{
    protected $elementType = 'Seomatic_FieldMeta';

    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'seoTitleSource'				=> array(AttributeType::Enum, 'values' => "custom,field", 'default' => 'field'),
            'seoTitleSourceField'			=> array(AttributeType::String, 'default' => 'title'),
            'seoDescriptionSource'			=> array(AttributeType::Enum, 'values' => "custom,field", 'default' => 'custom'),
            'seoDescriptionSourceField'     => array(AttributeType::String, 'default' => ''),
            'seoKeywordsSource'				=> array(AttributeType::Enum, 'values' => "custom,keywords,field", 'default' => 'custom'),
            'seoKeywordsSourceField'		=> array(AttributeType::String, 'default' => ''),
            'seoImageIdSource'				=> array(AttributeType::Enum, 'values' => "custom,field", 'default' => 'custom'),
            'seoImageIdSourceField'			=> array(AttributeType::String, 'default' => ''),
/* -- For Commerce products */
            'seoCommerceVariants'           => array(AttributeType::Mixed),
        ));
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return false;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        return "";
    }

} /* -- class Seomatic_MetaFieldModel */