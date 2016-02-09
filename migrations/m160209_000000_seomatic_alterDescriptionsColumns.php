<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m160209_000000_seomatic_alterDescriptionsColumns extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {

        $this->alterColumn('seomatic_settings', 'genericOwnerDescription', array(ColumnType::Varchar, 'maxLength' => 1024));
        $this->alterColumn('seomatic_settings', 'genericCreatorDescription', array(ColumnType::Varchar, 'maxLength' => 1024));

        // return true and let craft know its done
        return true;
    }
}
