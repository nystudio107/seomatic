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
            'locale'                    => array(AttributeType::String, 'default' => craft()->language),
            'elementId'                 => array(AttributeType::Number, 'default' => 0),
            'metaType'                  => array(AttributeType::Enum, 'values' => "default,template", 'default' => 'template'),
            'metaPath'                  => array(AttributeType::String, 'default' => ''),
            'seoMainEntityCategory'     => array(AttributeType::String, 'default' => 'CreativeWork'),
            'seoMainEntityOfPage'       => array(AttributeType::String, 'default' => 'WebPage'),
            'seoTitle'                  => array(AttributeType::String, 'default' => ''),
            'seoDescription'            => array(AttributeType::String, 'default' => ''),
            'seoKeywords'               => array(AttributeType::String, 'default' => ''),
            'seoImageTransform'         => array(AttributeType::String, 'default' => ''),
            'seoFacebookImageTransform' => array(AttributeType::String, 'default' => ''),
            'seoTwitterImageTransform'  => array(AttributeType::String, 'default' => ''),
            'twitterCardType'           => array(AttributeType::String, 'default' => ''),
            'openGraphType'             => array(AttributeType::String, 'default' => ''),
            'robots'                    => array(AttributeType::String, 'default' => ''),
            'seoImageId'                => array(AttributeType::Number, 'default' => null),
            'seoTwitterImageId'           => array(AttributeType::Number, 'default' => null),
            'seoFacebookImageId'          => array(AttributeType::Number, 'default' => null),
        ));
    }

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

    /**
     * Returns the seoTitle
     *
     * @return string
     */
    public function seoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Returns the seoDescription
     *
     * @return string
     */
    public function seoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Returns the seoKeywords
     *
     * @return string
     */
    public function seoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * Returns the seoImage as a url
     *
     * @return string
     */
    public function seoImage()
    {
        $result = "";
        if (isset($this->seoImageId))
        {
            $image = craft()->assets->getFileById($this->seoImageId);
            if ($image)
                $result = $image->url;
        }
        return $result;
    }

    /**
     * Returns the seoImageId
     *
     * @return string
     */
    public function seoImageId()
    {
        return $this->seoImageId;
    }

} /* -- class Seomatic_MetaModel */