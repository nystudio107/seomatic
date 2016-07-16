<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m160715_000000_seomatic_addSeoImageTransforms extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 *
	 * @return bool
	 */
	public function safeUp()
	{

		// Trim the fat

        $this->alterColumn('seomatic_settings', 'siteSeoTitleSeparator', array(ColumnType::Varchar, 'maxLength' => 10));
        $this->alterColumn('seomatic_settings', 'siteRobots', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'siteTwitterCardType', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'siteOpenGraphType', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'siteLinksQueryInput', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'googleSiteVerification', array(ColumnType::Varchar, 'maxLength' => 100));
        $this->alterColumn('seomatic_settings', 'bingSiteVerification', array(ColumnType::Varchar, 'maxLength' => 100));
        $this->alterColumn('seomatic_settings', 'googleAnalyticsUID', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'googleTagManagerID', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'siteOwnerType', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'siteOwnerSubType', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'siteOwnerSpecificType', array(ColumnType::Varchar, 'maxLength' => 50));

        $this->alterColumn('seomatic_settings', 'twitterHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'facebookHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'facebookProfileId', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'facebookAppId', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'linkedInHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'googlePlusHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'youtubeHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'youtubeChannelHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'instagramHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'pinterestHandle', array(ColumnType::Varchar, 'maxLength' => 50));
        $this->alterColumn('seomatic_settings', 'githubHandle', array(ColumnType::Varchar, 'maxLength' => 50));

		// specify columns and AttributeType
		$newColumns = array (
			'seoImageTransform' => ColumnType::Varchar,
			'seoFacebookImageTransform' => ColumnType::Varchar,
			'seoTwitterImageTransform' => ColumnType::Varchar
		);

		$this->_addColumnsAfter("seomatic_meta", $newColumns, "seoKeywords");

		// specify columns and AttributeType
		$newColumns = array (
			'siteSeoImageTransform' => ColumnType::Varchar,
			'siteSeoFacebookImageTransform' => ColumnType::Varchar,
			'siteSeoTwitterImageTransform' => ColumnType::Varchar
		);

		$this->_addColumnsAfter("seomatic_settings", $newColumns, "siteSeoKeywords");

		// return true and let craft know its done
		return true;
	}

	private function _addColumnsAfter($tableName, $newColumns, $afterColumnHandle)
	{

		// this is a foreach loop, enough said
		foreach ($newColumns as $columnName => $columnType)
		{
			// check if the column does NOT exist
			if (!craft()->db->columnExists($tableName, $columnName))
			{
				$this->addColumnAfter($tableName, $columnName, array(
					'column' => $columnType,
					'null'   => false,
					),
					$afterColumnHandle
				);

				// log that we created the new column
				SeomaticPlugin::log("Created the `$columnName` in the `$tableName` table.", LogLevel::Info, true);

			}

			// if the column already exists in the table
			else {

				// tell craft that we couldn't create the column as it alredy exists.
				SeomaticPlugin::log("Column `$columnName` already exists in the `$tableName` table.", LogLevel::Info, true);

			}
		}
	}
}
