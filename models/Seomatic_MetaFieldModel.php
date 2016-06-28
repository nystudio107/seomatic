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
            'seoTitleUnparsed'              => array(AttributeType::String, 'default' => ''),
            'seoDescriptionUnparsed'        => array(AttributeType::String, 'default' => ''),
            'seoKeywordsUnparsed'           => array(AttributeType::String, 'default' => ''),
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
     * Returns the seoTitleUnparsed
     *
     * @return string
     */
    public function seoTitleUnparsed()
    {
        return $this->seoTitleUnparsed;
    }

    /**
     * Returns the seoDescriptionUnparsed
     *
     * @return string
     */
    public function seoDescriptionUnparsed()
    {
        return $this->seoDescriptionUnparsed;
    }

    /**
     * Returns the seoKeywordsUnparsed
     *
     * @return string
     */
    public function seoKeywordsUnparsed()
    {
        return $this->seoKeywordsUnparsed;
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