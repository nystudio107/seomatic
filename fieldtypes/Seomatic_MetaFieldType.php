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
        if (isset($this->element))
        {

        $id = craft()->templates->formatInputId($name);
        $namespacedId = craft()->templates->namespaceInputId($id);

        // Include our Javascript & CSS
        craft()->templates->includeCssResource('seomatic/css/css-reset.css');
        craft()->templates->includeCssResource('seomatic/css/prism.min.css');
        craft()->templates->includeCssResource('seomatic/css/bootstrap-tokenfield.css');
        craft()->templates->includeCssResource('seomatic/css/style.css');
        craft()->templates->includeCssResource('seomatic/css/field.css');
        craft()->templates->includeJsResource('seomatic/js/main_entity_type_list.js');
        craft()->templates->includeJs("var metaFieldPrefix='" . $namespacedId . "';");
        craft()->templates->includeJsResource('seomatic/js/meta.js');
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

        if (isset($variables['locale']))
            $locale = $variables['locale'];
        else
            $locale = craft()->language;

        $siteMeta = craft()->seomatic->getSiteMeta($locale);
        $titleLength = craft()->config->get("maxTitleLength", "seomatic");
        if ($siteMeta['siteSeoTitlePlacement'] == "none")
            $variables['titleLength'] = $titleLength;
        else
            $variables['titleLength'] = ($titleLength - strlen(" | ") - strlen($siteMeta['siteSeoName']));

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

        // Set asset ID
        $variables['seoTwitterImageId'] = $variables['meta']->seoTwitterImageId;

        // Set asset elements
        if ($variables['seoTwitterImageId']) {
            if (is_array($variables['seoTwitterImageId'])) {
                $variables['seoTwitterImageId'] = $variables['seoTwitterImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['seoTwitterImageId']);
            $variables['elementsTwitter'] = array($asset);
        } else {
            $variables['elementsTwitter'] = array();
        }

        // Set asset ID
        $variables['seoFacebookImageId'] = $variables['meta']->seoFacebookImageId;

        // Set asset elements
        if ($variables['seoFacebookImageId']) {
            if (is_array($variables['seoFacebookImageId'])) {
                $variables['seoFacebookImageId'] = $variables['seoFacebookImageId'][0];
            }
            $asset = craft()->elements->getElementById($variables['seoFacebookImageId']);
            $variables['elementsFacebook'] = array($asset);
        } else {
            $variables['elementsFacebook'] = array();
        }

        // Set element type
        $variables['elementType'] = craft()->elements->getElementType(ElementType::Asset);

        $variables['assetSources'] = $this->getSettings()->assetSources;

        $variables['seoTitleSourceChangeable'] = $this->getSettings()->seoTitleSourceChangeable;
        $variables['seoDescriptionSourceChangeable'] = $this->getSettings()->seoDescriptionSourceChangeable;
        $variables['seoKeywordsSourceChangeable'] = $this->getSettings()->seoKeywordsSourceChangeable;
        $variables['seoImageIdSourceChangeable'] = $this->getSettings()->seoImageIdSourceChangeable;
        $variables['seoTwitterImageIdSourceChangeable'] = $this->getSettings()->seoTwitterImageIdSourceChangeable;
        $variables['seoFacebookImageIdSourceChangeable'] = $this->getSettings()->seoFacebookImageIdSourceChangeable;
        $variables['twitterCardTypeChangeable'] = $this->getSettings()->twitterCardTypeChangeable;
        $variables['openGraphTypeChangeable'] = $this->getSettings()->openGraphTypeChangeable;
        $variables['robotsChangeable'] = $this->getSettings()->robotsChangeable;

        $variables['transformsList'] = craft()->seomatic->getTransformsList();

/* -- Extract a list of the other plain text fields that are in this entry's layout */

        $fieldList = array('title' => 'Title');
        $fieldData = array('title' => $this->element->content['title']);
        $fieldImage = array();
        $imageFieldList = array();
        $fieldLayouts = $this->element->fieldLayout->getFields();
        foreach ($fieldLayouts as $fieldLayout)
        {
            $field = craft()->fields->getFieldById($fieldLayout->fieldId);

            switch ($field->type)
            {
                case "PlainText":
                case "RichText":
                case "RedactorI":
                case "PreparseField_Preparse":
                    $fieldList[$field->handle] = $field->name;
                    $fieldData[$field->handle] = craft()->seomatic->truncateStringOnWord(
                            strip_tags($this->element->content[$field->handle]),
                            200);
                    break;

                case "Neo":
                    $fieldList[$field->handle] = $field->name;
                    $fieldData[$field->handle] = craft()->seomatic->truncateStringOnWord(
                            craft()->seomatic->extractTextFromNeo($this->element[$field->handle]),
                            200);
                    break;

                case "Matrix":
                    $fieldList[$field->handle] = $field->name;
                    $fieldData[$field->handle] = craft()->seomatic->truncateStringOnWord(
                            craft()->seomatic->extractTextFromMatrix($this->element[$field->handle]),
                            200);
                    break;

                case "Tags":
                    $fieldList[$field->handle] = $field->name;
                    $fieldData[$field->handle] = craft()->seomatic->truncateStringOnWord(
                            craft()->seomatic->extractTextFromTags($this->element[$field->handle]),
                            200);
                    break;

                case "FocusPoint_FocusPoint":
                case "Assets":
                    $imageFieldList[$field->handle] = $field->name;
                    $img = $this->element[$field->handle]->first();
                    if ($img)
                        {
                            $fieldImage[$field->handle] = $img->url;
                        }
                    break;
            }
        }
        $variables['fieldList'] = $fieldList;
        $variables['imageFieldList'] = $imageFieldList;
        $variables['elementId'] = $this->element->id;
        $jsonVars['fieldData'] = $fieldData;
        $jsonVars['fieldImage'] = $fieldImage;
        $jsonVars['missing_image'] = UrlHelper::getResourceUrl('seomatic/images/missing_image.png');
        $jsonVars = json_encode($jsonVars);
        craft()->templates->includeJs("$('#{$namespacedId}').SeomaticFieldType(" . $jsonVars . ");");
        return craft()->templates->render('seomatic/field', $variables);
        }
    }

    /**
     * Define our settings
     * @return none
     */
    protected function defineSettings()
        {
            return array(
                'assetSources' => AttributeType::Mixed,

                'seoMainEntityCategory' => array(AttributeType::String, 'default' => 'CreativeWork'),
                'seoMainEntityOfPage' => array(AttributeType::String, 'default' => 'WebPage'),

                'seoTitle' => AttributeType::String,
                'seoTitleSource' => array(AttributeType::String, 'default' => 'field'),
                'seoTitleSourceField' => array(AttributeType::String, 'default' => 'title'),
                'seoTitleSourceChangeable' => array(AttributeType::Bool, 'default' => 1),

                'seoDescription' => AttributeType::String,
                'seoDescriptionSource' => AttributeType::String,
                'seoDescriptionSourceField' => AttributeType::String,
                'seoDescriptionSourceChangeable' => array(AttributeType::Bool, 'default' => 1),

                'seoKeywords' => AttributeType::String,
                'seoKeywordsSource' => AttributeType::String,
                'seoKeywordsSourceField' => AttributeType::String,
                'seoKeywordsSourceChangeable' => array(AttributeType::Bool, 'default' => 1),

                'seoImageIdSource' => AttributeType::String,
                'seoImageIdSourceField' => AttributeType::String,
                'seoImageIdSourceChangeable' => array(AttributeType::Bool, 'default' => 1),
                'seoImageTransform' => AttributeType::String,

                'twitterCardType' => AttributeType::String,
                'twitterCardTypeChangeable' => array(AttributeType::Bool, 'default' => 1),
                'seoTwitterImageIdSource' => AttributeType::String,
                'seoTwitterImageIdSourceField' => AttributeType::String,
                'seoTwitterImageIdSourceChangeable' => array(AttributeType::Bool, 'default' => 1),
                'seoTwitterImageTransform' => AttributeType::String,

                'openGraphType' => AttributeType::String,
                'openGraphTypeChangeable' => array(AttributeType::Bool, 'default' => 1),
                'seoFacebookImageIdSource' => AttributeType::String,
                'seoFacebookImageIdSourceField' => AttributeType::String,
                'seoFacebookImageIdSourceChangeable' => array(AttributeType::Bool, 'default' => 1),
                'seoFacebookImageTransform' => AttributeType::String,

                'robots' => AttributeType::String,
                'robotsChangeable' => array(AttributeType::Bool, 'default' => 1),
            );
        }

    /**
     * Render the field settings
     * @return none
     */
    public function getSettingsHtml()
    {
        $locale = craft()->language;
        $siteMeta = craft()->seomatic->getSiteMeta($locale);

        $fields = craft()->fields->getAllFields();

        $fieldList = array('title' => 'Title');
        $imageFieldList = array();
        foreach ($fields as $field)
        {

            switch ($field->type)
            {
                case "PlainText":
                case "RichText":
                case "RedactorI":
                case "PreparseField_Preparse":
                    $fieldList[$field->handle] = $field->name;
                    break;

                case "Matrix":
                    $fieldList[$field->handle] = $field->name;
                    break;

                case "Neo":
                    $fieldList[$field->handle] = $field->name;
                    break;

                case "Tags":
                    $fieldList[$field->handle] = $field->name;
                    break;

                case "FocusPoint_FocusPoint":
                case "Assets":
                    $imageFieldList[$field->handle] = $field->name;
                    break;
            }
        }

        $titleLength = craft()->config->get("maxTitleLength", "seomatic");
        if ($siteMeta['siteSeoTitlePlacement'] == "none")
            $titleLength = $titleLength;
        else
            $titleLength = ($titleLength - strlen(" | ") - strlen($siteMeta['siteSeoName']));

        craft()->templates->includeCssResource('seomatic/css/bootstrap-tokenfield.css');
        craft()->templates->includeCssResource('seomatic/css/style.css');
        craft()->templates->includeCssResource('seomatic/css/field.css');
        craft()->templates->includeJsResource('seomatic/js/main_entity_type_list.js');
        craft()->templates->includeJs("var metaFieldPrefix='types-Seomatic_Meta-';");
        craft()->templates->includeJsResource('seomatic/js/field_settings.js');
        craft()->templates->includeJsResource('seomatic/js/meta.js');
        craft()->templates->includeJsResource('seomatic/js/bootstrap-tokenfield.min.js');

        $assetElementType = craft()->elements->getElementType(ElementType::Asset);
        return craft()->templates->render('seomatic/field_settings', array(
            'assetSources'          => $this->getElementSources($assetElementType),
            'fieldList'             => $fieldList,
            'imageFieldList'        => $imageFieldList,
            'titleLength'           => $titleLength,
            'transformsList'        => craft()->seomatic->getTransformsList(),
            'settings'              => $this->getSettings()
        ));
   }

    /**
     * [prepValueFromPost description]
     * @param  [type] $value [description]
     * @return none          n/a
     */
    public function prepValueFromPost($value)
    {
        $result = null;

        if (empty($value))
        {
            $value = $this->prepValue($value);
        }
        else
        {
            $value = new Seomatic_MetaFieldModel($value);
            $value = $this->prepValue($value);
        }


/* -- Handle pulling values from other fields */

        $element = $this->element;
        if ($value->seoTitleUnparsed == "")
            $value->seoTitleUnparsed = $value->seoTitle;
        if ($value->seoDescriptionUnparsed == "")
            $value->seoDescriptionUnparsed = $value->seoDescription;
        if ($value->seoKeywordsUnparsed == "")
            $value->seoKeywordsUnparsed = $value->seoKeywords;

/* -- If we're attached to a Commerce_Product element, always have the Main Enity of Page be a Product */

        $elemType = $element->getElementType();
        if ($elemType == "Commerce_Product")
        {
            $value->seoMainEntityCategory = "Product";
            $value->seoMainEntityOfPage = "";
        }

        if ($element)
        {
    /* -- Swap in any SEOmatic fields that are pulling from other entry fields */

            switch ($value->seoTitleSource)
            {
                case 'field':
                    if (isset($element[$value->seoTitleSourceField]))
                    {
                        $value->seoTitle = craft()->seomatic->getTextFromEntryField($element[$value->seoTitleSourceField]);
                        if (craft()->config->get("truncateTitleTags", "seomatic"))
                        {
                            $truncLength = craft()->config->get("maxTitleLength", "seomatic");
                            $value->seoTitle = craft()->seomatic->truncateStringOnWord($value->seoTitle, $truncLength);
                        }
                    }
                break;

                case 'custom':
                    $value->seoTitle = craft()->seomatic->parseAsTemplate($value->seoTitleUnparsed, $element);
                break;
            }

            switch ($value->seoDescriptionSource)
            {
                case 'field':
                    if (isset($element[$value->seoDescriptionSourceField]))
                    {
                        $value->seoDescription = craft()->seomatic->getTextFromEntryField($element[$value->seoDescriptionSourceField]);
                        if (craft()->config->get("truncateDescriptionTags", "seomatic"))
                        {
                            $truncLength = craft()->config->get("maxDescriptionLength", "seomatic");
                            $value->seoDescription = craft()->seomatic->truncateStringOnWord($value->seoDescription, $truncLength);
                        }
                    }
                break;

                case 'custom':
                    $value->seoDescription = craft()->seomatic->parseAsTemplate($value->seoDescriptionUnparsed, $element);
               break;
            }

            switch ($value->seoKeywordsSource)
            {
                case 'field':
                    if (isset($element[$value->seoKeywordsSourceField]))
                    {
                        $value->seoKeywords = craft()->seomatic->getTextFromEntryField($element[$value->seoKeywordsSourceField]);
                        if (craft()->config->get("truncateKeywordsTags", "seomatic"))
                        {
                            $truncLength = craft()->config->get("maxKeywordsLength", "seomatic");
                            $value->seoKeywords = craft()->seomatic->truncateStringOnWord($value->seoKeywords, $truncLength);
                        }
                    }
                break;

                case 'keywords':
                    if (isset($element[$value->seoKeywordsSourceField]))
                    {
                        $text = craft()->seomatic->getTextFromEntryField($element[$value->seoKeywordsSourceField]);
                        $value->seoKeywords = craft()->seomatic->extractKeywords($text);
                    }
                break;

                case 'custom':
                    $value->seoKeywords = craft()->seomatic->parseAsTemplate($value->seoKeywordsUnparsed, $element);
               break;
            }

            switch ($value->seoImageIdSource)
            {
                case 'field':
                    if (isset($element[$value->seoImageIdSourceField]) && isset($element[$value->seoImageIdSourceField][0]))
                    {
                        $value->seoImageId = $element[$value->seoImageIdSourceField][0]->id;
                    }
                break;
            }

            switch ($value->seoTwitterImageIdSource)
            {
                case 'field':
                    if (isset($element[$value->seoTwitterImageIdSourceField]) && isset($element[$value->seoTwitterImageIdSourceField][0]))
                    {
                        $value->seoTwitterImageId = $element[$value->seoTwitterImageIdSourceField][0]->id;
                    }
                break;
            }

            switch ($value->seoFacebookImageIdSource)
            {
                case 'field':
                    if (isset($element[$value->seoFacebookImageIdSourceField]) && isset($element[$value->seoFacebookImageIdSourceField][0]))
                    {
                        $value->seoFacebookImageId = $element[$value->seoFacebookImageIdSourceField][0]->id;
                    }
                break;
            }

        }
        return $value;
    }

    public function prepValue($value)
    {

        if (!$value)
        {
            $value = new Seomatic_MetaFieldModel();

            $value->seoMainEntityCategory = $this->getSettings()->seoMainEntityCategory;
            $value->seoMainEntityOfPage = $this->getSettings()->seoMainEntityOfPage;

            $value->seoTitle = $this->getSettings()->seoTitle;
            $value->seoTitleUnparsed = $this->getSettings()->seoTitle;
            $value->seoTitleSource = $this->getSettings()->seoTitleSource;
            $value->seoTitleSourceField = $this->getSettings()->seoTitleSourceField;

            $value->seoDescription = $this->getSettings()->seoDescription;
            $value->seoDescriptionUnparsed = $this->getSettings()->seoDescription;
            $value->seoDescriptionSource = $this->getSettings()->seoDescriptionSource;
            $value->seoDescriptionSourceField = $this->getSettings()->seoDescriptionSourceField;

            $value->seoKeywords = $this->getSettings()->seoKeywords;
            $value->seoKeywordsUnparsed = $this->getSettings()->seoKeywords;
            $value->seoKeywordsSource = $this->getSettings()->seoKeywordsSource;
            $value->seoKeywordsSourceField = $this->getSettings()->seoKeywordsSourceField;

            $value->seoImageIdSource = $this->getSettings()->seoImageIdSource;
            $value->seoImageIdSourceField = $this->getSettings()->seoImageIdSourceField;
            $value->seoImageTransform = $this->getSettings()->seoImageTransform;

            $value->twitterCardType = $this->getSettings()->twitterCardType;
            $value->seoTwitterImageIdSource = $this->getSettings()->seoTwitterImageIdSource;
            $value->seoTwitterImageIdSourceField = $this->getSettings()->seoTwitterImageIdSourceField;
            $value->seoTwitterImageTransform = $this->getSettings()->seoTwitterImageTransform;

            $value->openGraphType = $this->getSettings()->openGraphType;
            $value->seoFacebookImageIdSource = $this->getSettings()->seoFacebookImageIdSource;
            $value->seoFacebookImageIdSourceField = $this->getSettings()->seoFacebookImageIdSourceField;
            $value->seoFacebookImageTransform = $this->getSettings()->seoFacebookImageTransform;

            $value->robots = $this->getSettings()->robots;
        }

        if (craft()->request->isSiteRequest())
        {
        }

        return $value;
    }

    /**
     * @inheritDoc IFieldType::onAfterElementSave()
     *
     * @return null
     */
    public function onAfterElementSave()
    {
        $element = $this->element;
        $content = $element->getContent();
        $fieldHandle = $this->model->handle;
        $shouldResave = false;

        if (empty($fieldHandle))
            $shouldResave = true;
        if (!isset($content[$fieldHandle]))
            $shouldResave = true;
        else
        {
            if (empty($content[$fieldHandle]))
                $shouldResave = true;
        }

// We should always re-save here, in case they changed the source for some fields or such
//        if ($shouldResave)
        if (true)
        {
            if ($content)
                $defaultField = $this->prepValueFromPost($content[$fieldHandle]);
            else
                $defaultField = $this->prepValueFromPost(null);
            $content->setAttribute($fieldHandle, $defaultField);
            $element->setContent($content);
            craft()->content->saveContent($element);
        }

        parent::onAfterElementSave();
    }

    /**
     * Returns sources avaible to an element type.
     *
     * @access protected
     * @return mixed
     */
    protected function getElementSources($elementType)
    {
        $sources = array();

        foreach ($elementType->getSources() as $key => $source)
        {
            if (!isset($source['heading']))
            {
                $sources[] = array('label' => $source['label'], 'value' => $key);
            }
        }

        return $sources;
    }

} /* -- Seomatic_MetaFieldType */