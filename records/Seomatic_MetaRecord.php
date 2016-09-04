<?php
namespace Craft;

class Seomatic_MetaRecord extends BaseRecord
{

    public function getTableName()
    {
        return 'seomatic_meta';
    }

    protected function defineAttributes()
    {
        return array(
            'locale'			        => array(AttributeType::String, 'default' => craft()->language),
            'elementId'			        => array(AttributeType::Number, 'default' => 0),
            'metaType'                  => array(AttributeType::Enum, 'values' => "default,template", 'default' => 'template'),
            'metaPath'                  => array(AttributeType::String, 'default' => ''),
            'seoMainEntityCategory'     => array(AttributeType::String, 'default' => ''),
            'seoMainEntityOfPage'       => array(AttributeType::String, 'default' => ''),
            'seoTitle'                  => array(AttributeType::String, 'default' => ''),
            'seoDescription'            => array(AttributeType::String, 'default' => ''),
            'seoKeywords'               => array(AttributeType::String, 'default' => ''),
            'seoImageTransform'         => array(AttributeType::String, 'default' => ''),
            'seoFacebookImageTransform' => array(AttributeType::String, 'default' => ''),
            'seoTwitterImageTransform'  => array(AttributeType::String, 'default' => ''),
            'twitterCardType'	        => array(AttributeType::String, 'default' => 'summary'),
            'openGraphType'		        => array(AttributeType::String, 'default' => 'website'),
            'robots'			        => array(AttributeType::String, 'default' => ''),
/* -- This is defined in definteRelations() below, of note:
      You don’t need to specify the foreign key column name in BELONGS_TO relations (defaults to the relation name appended with “Id”)
      https://craftcms.com/docs/plugins/records
            'seoImageId'        => array(AttributeType::Number, 'default' => 0),
*/

        );
    }

    public function defineRelations()
    {
        return array(
            'element'  => array(static::BELONGS_TO, 'ElementRecord', 'id', 'required' => true, 'onDelete' => static::CASCADE),
            'seoImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
            'seoTwitterImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
            'seoFacebookImage' => array(static::BELONGS_TO, 'AssetFileRecord'), /* -- "Id" is automatically appended */
        );
    }

} /* -- class Seomatic_MetaRecord */