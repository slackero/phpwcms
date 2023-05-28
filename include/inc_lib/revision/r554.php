<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2023, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// Revision 554 Update Check
function phpwcms_revision_r554() {

	$status = true;

	// do former revision check – fallback to r553
	if(phpwcms_revision_check_temp('553') !== true) {
		$status = phpwcms_revision_check('553');
	}

    // Update DATE/TIME 0000-00-00 00:00:00 to NULL

    /*
    ALTER TABLE `phpwcms_ads_campaign`
        ALTER `adcampaign_created` DROP DEFAULT,
        ALTER `adcampaign_changed` DROP DEFAULT,
        ALTER `adcampaign_datestart` DROP DEFAULT,
        ALTER `adcampaign_dateend` DROP DEFAULT;
    ALTER TABLE `phpwcms_ads_campaign`
        CHANGE `adcampaign_created` `adcampaign_created` DATETIME NULL,
        CHANGE `adcampaign_changed` `adcampaign_changed` DATETIME NULL,
        CHANGE `adcampaign_datestart` `adcampaign_datestart` DATETIME NULL,
        CHANGE `adcampaign_dateend` `adcampaign_dateend` DATETIME NULL;
    */

    /*
    ALTER TABLE `phpwcms_ads_formats`
        ALTER `adformat_created` DROP DEFAULT,
        ALTER `adformat_changed` DROP DEFAULT;
    ALTER TABLE `phpwcms_ads_formats`
        CHANGE `adformat_created` `adformat_created` DATETIME NULL,
        CHANGE `adformat_changed` `adformat_changed` DATETIME NULL;
    */

    /*
    ALTER TABLE `phpwcms_ads_place`
        ALTER `adplace_created` DROP DEFAULT,
        ALTER `adplace_changed` DROP DEFAULT;
    ALTER TABLE `phpwcms_ads_place`
        CHANGE `adplace_created` `adplace_created` DATETIME NULL,
        CHANGE `adplace_changed` `adplace_changed` DATETIME NULL;
    */

    /*
    ALTER TABLE `phpwcms_ads_tracking`
        ALTER `adtracking_created` DROP DEFAULT;
    ALTER TABLE `phpwcms_ads_tracking`
        CHANGE `adtracking_created` `adtracking_created` DATETIME NULL;
    */

    /*
    ALTER TABLE `phpwcms_article`
        ALTER `article_begin` DROP DEFAULT,
        ALTER `article_end` DROP DEFAULT;
    ALTER TABLE `phpwcms_article`
        CHANGE `article_begin` `article_begin` DATETIME NULL,
        CHANGE `article_end` `article_end` DATETIME NULL;
    */

	return $status;
}
