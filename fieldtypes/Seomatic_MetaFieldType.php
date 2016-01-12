<?php
namespace Craft;

/**
 * Seomatic Meta field type
 */
class Seomatic_MetaFieldType extends BaseFieldType
{

    public function getName()
    {
        return Craft::t('SEOmatic Meta');
    }

    public function defineContentAttribute()
    {
        return AttributeType::Mixed;
    }

    public function getInputHtml($name, $value)
    {

        if (!$value)
            $value = new Seomatic_MetaFieldModel();

        $id = craft()->templates->formatInputId($name);
        $namespacedId = craft()->templates->namespaceInputId($id);

        // Include our Javascript & CSS
        craft()->templates->includeCssResource('seomatic/css/css-reset.css');
        craft()->templates->includeCssResource('seomatic/css/prism.min.css');
        craft()->templates->includeCssResource('seomatic/css/bootstrap-tokenfield.css');
        craft()->templates->includeCssResource('seomatic/css/style.css');
        craft()->templates->includeCssResource('seomatic/css/field.css');
        craft()->templates->includeJsResource('seomatic/js/field.js');
        craft()->templates->includeJsResource('seomatic/js/jquery.bpopup.min.js');
        craft()->templates->includeJsResource('seomatic/js/prism.min.js');
        craft()->templates->includeJsResource('seomatic/js/bootstrap-tokenfield.min.js');

        $variables = array(
            'id' => $id,
            'name' => $name,
            'meta' => $value,
            'element' => $this->element,
            'field' => $this->model,
            );

        $jsonVars = array(
            'id' => $id,
            'name' => $name,
            'namespace' => $namespacedId,
            'prefix' => craft()->templates->namespaceInputId(""),
            );

        $jsonVars = json_encode($jsonVars);
        craft()->templates->includeJs("$('#{$namespacedId}').SeomaticFieldType(" . $jsonVars . ");");

        if (isset($variables['locale']))
            $locale = $variables['locale'];
        else
            $locale = craft()->language;

        $siteMeta = craft()->seomatic->getSiteMeta($locale);
        if ($siteMeta['siteSeoTitlePlacement'] == "none")
            $variables['titleLength'] = 70;
        else
            $variables['titleLength'] = (70 - strlen(" | ") - strlen($siteMeta['siteSeoName']));

/* -- Prep some parameters */

        // Whether any assets sources exist
        $sources = craft()->assets->findFolders();
        $variables['assetsSourceExists'] = count($sources);

        // URL to create a new assets source
        $variables['newAssetsSourceUrl'] = UrlHelper::getUrl('settings/assets/sources/new');

        // Set asset ID
        $variables['seoImageId'] = $variables['meta']->seoImageId;

        // Set asset elements
        if ($variables['seoImageId']) {
            if (is_array($variables['seoImageId'])) {
                $variables['seoImageId'] = $variables['seoImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['seoImageId']);
            $variables['elements'] = array($asset);
        } else {
            $variables['elements'] = array();
        }

        // Set element type
        $variables['elementType'] = craft()->elements->getElementType(ElementType::Asset);

/* -- Extract a list of the other plain text fields that are in this entry's layout */

        $fieldList = array('title' => 'Title');
        $imageFieldList = array();
        $fieldLayouts = $this->element->fieldLayout->getFields();
        foreach ($fieldLayouts as $fieldLayout)
        {
            $field = craft()->fields->getFieldById($fieldLayout->fieldId);
            if ($field->fieldType->name == "Plain Text"
                || $field->fieldType->name == "Rich Text"
                || $field->fieldType->name == "Rich Text (Redactor I)"
                || $field->fieldType->name == "Matrix"
                )
                $fieldList[$field->handle] = $field->name;
            if ($field->fieldType->name == "Assets")
                $imageFieldList[$field->handle] = $field->name;
        }
        $variables['fieldList'] = $fieldList;
        $variables['imageFieldList'] = $imageFieldList;
        $variables['elementId'] = $this->element->id;

        return craft()->templates->render('seomatic/field', $variables);
    }

    /**
     * Define our settings
     * @return none
     */
    protected function defineSettings()
        {
            return array(
            );
        }

    /**
     * Render the field settings
     * @return none
     */
    public function getSettingsHtml()
    {
    }

    /**
     * [prepValueFromPost description]
     * @param  [type] $value [description]
     * @return none          n/a
     */
    public function prepValueFromPost($value)
    {

        if (empty($value))
        {
            return new Seomatic_MetaFieldModel();
        }
        else
        {
            return new Seomatic_MetaFieldModel($value);
        }
    }

    public function prepValue($value)
    {

        if (craft()->request->isSiteRequest())
        {
        }

        return $value;
    }

} /* -- Seomatic_MetaFieldType */