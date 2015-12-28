<?php
namespace Craft;

class Seomatic_MetaElementType extends BaseElementType
{

    public function getName()
    {
        return Craft::t('Meta');
    }

    /**
     * Returns whether this element type has content.
     *
     * @return bool
     */
    public function hasContent()
    {
        return true;
    }

    /**
     * Returns whether this element type has titles.
     *
     * @return bool
     */
    public function hasTitles()
    {
        return true;
    }

    /**
     * Returns whether this element type can have statuses.
     *
     * @return bool
     */
    public function hasStatuses()
    {
        return false;
    }

    /**
     * Returns whether this element type is localized.
     *
     * @return bool
     */
    public function isLocalized()
    {
        return true;
    }
    
    /**
     * Returns this element type's sources.
     *
     * @param string|null $context
     * @return array|false
     */
    public function getSources($context = null)
    {

        $sources = array(
            '*' => array(
                'label' => Craft::t('Template Metas'),
                'criteria' => array('metaType' => '*')
            ),
        );

        return $sources;
    }

    /**
     * @inheritDoc IElementType::getAvailableActions()
     *
     * @param string|null $source
     *
     * @return array|null
     */
    public function getAvailableActions($source = null)
    {
/* Not sure if I like this UI
        $deleteAction = craft()->elements->getAction('Delete');

        $deleteAction->setParams(
            array(
                'confirmationMessage' => Craft::t('Are you sure you want to delete the selected Template Metas?'),
                'successMessage'      => Craft::t('Forms deleted.'),
            )
        );

        return array($deleteAction);
*/
    }

    /**
     * Returns the attributes that can be shown/sorted by in table views.
     *
     * @param string|null $source
     * @return array
     */
    public function defineTableAttributes($source = null)
    {
        return array(
            'title'             => Craft::t('Title'),
            'metaPath'  => Craft::t('Template Path'),
        );
    }

    /**
     * Returns the table view HTML for a given attribute.
     *
     * @param BaseElementModel $element
     * @param string $attribute
     * @return string
     */
    public function getTableAttributeHtml(BaseElementModel $element, $attribute)
    {
        switch ($attribute)
        {

            case 'title':
            {
                return $element->$attribute;
            }

            case 'metaPath':
            {
                return $element->$attribute;
            }

            default:
            {
                return parent::getTableAttributeHtml($element, $attribute);
            }
        }
    }

    /**
     * Defines any custom element criteria attributes for this element type.
     *
     * @return array
     */
    public function defineCriteriaAttributes()
    {
        return array(
            'metaType'  => array(AttributeType::Enum, 'values' => "default,template", 'default' => '*'),
        );
    }

    /**
     * Modifies an element query targeting elements of this type.
     *
     * @param DbCommand $query
     * @param ElementCriteriaModel $criteria
     * @return mixed
     */
    public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
    {
	    /*
        $query
            ->addSelect('seomatic_meta.*')
            ->join('seomatic_meta seomatic_meta', 'seomatic_meta.id = elements.id');
*/
/*
        if ($criteria->metaType)
        {
            $query->andWhere(DbHelper::parseParam('seomatic_meta.metaType', $criteria->metaType, $query->params));
        }
*/
        $query
            ->addSelect('seomatic_meta.*')
            ->join('seomatic_meta seomatic_meta', 'seomatic_meta.elementid = elements.id')
        	->andWhere(DbHelper::parseParam('seomatic_meta.locale', $criteria->locale, $query->params));

   }

    /**
     * Populates an element model based on a query result.
     *
     * @param array $row
     * @return array
     */
    public function populateElementModel($row)
    {
        return Seomatic_MetaModel::populateModel($row);
    }

    /**
     * Returns the HTML for an editor HUD for the given element.
     *
     * @param BaseElementModel $element
     * @return string
     */
    /*
    public function getEditorHtml(BaseElementModel $element)
    {
        // Start/End Dates
        $html = craft()->templates->render('semomatic/_edit', array(
            'element' => $element,
        ));

        // Everything else
        $html .= parent::getEditorHtml($element);

        return $html;
    }
    */
} /* -- class Seomatic_MetaElementType */
