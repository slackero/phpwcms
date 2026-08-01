<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 544 Update Check
function phpwcms_revision_r544() {

    $status = true;


    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_calendar` WHERE Field='calendar_refid'");

    if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 3) === 'int') {

        if($result = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_calendar` CHANGE `calendar_refid` `calendar_refid` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER')) {

            _dbUpdate('phpwcms_calendar', array('calendar_refid' => ''), "calendar_refid='0'");

        }

    }

    //modifications

    // Add column newsletter_pub for Newsletter modification
    if(!_dbColumnExists('phpwcms_newsletter', 'newsletter_pub')) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_newsletter ADD newsletter_pub datetime DEFAULT NULL", 'ALTER');
    }
    // Add column newsletter_lang for Newsletter modification
    if(!_dbColumnExists('phpwcms_newsletter', 'newsletter_lang')) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_newsletter ADD newsletter_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    }
    // Add column queue_opener for opene newsletter counter
    if(!_dbColumnExists('phpwcms_newsletterqueue', 'queue_opener')) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_newsletterqueue ADD queue_opener INT(11) NOT NULL DEFAULT '0'", 'ALTER');
    }

    // Add column f_alias for filealias modification
    if(!_dbColumnExists('phpwcms_file', 'f_alias')) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_file ADD f_alias VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    }

    // Add column group_modkey for usergroup modules
    if(!_dbColumnExists('phpwcms_usergroup', 'group_modkey')) {
       $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_usergroup ADD group_modkey VARCHAR(20) NOT NULL AFTER `group_active`", 'ALTER');
    }

    // Add column group_sys for adding Sysrecords to user groups
    if(!_dbColumnExists('phpwcms_usergroup', 'group_syskey')) {
        if(_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_usergroup ADD group_syskey VARCHAR(10) NOT NULL AFTER `group_active`", 'ALTER')) {
            //now we add new sys groups to phpwcms_usergroup
            //first we get all admin users and prepare insert value
            $adminusers = _dbQuery('SELECT `usr_id` FROM `'.DB_PREPEND.'phpwcms_user` WHERE `usr_admin` = 1');
            if(count($adminusers)) {
                foreach ($adminusers as $admins) {
                    $adminids[] = $admins['usr_id'];
                }
                $group_member = implode(',',$adminids);
            } else {
                $group_member = '';
            }
            //basic data for sys group
            $data = array(
                'group_name' => 'SYSGROUP',
                'group_member' => $group_member ,
                'group_value' => '',
                'group_active' => 1,
                'group_trash' => 0,
                'group_syskey' => ''
            );

            //array with all sys group values
            $newgroupnames = array(
                'artcent',
                'artnew',
                'artnews',
                'module',
                'adm',
                'admlayout',
                'admtempl',
                'admuser',
                'admugroup',
                'admfc',
                'admalias',
                'admimagealias',
                'admctptempl',
                'admlink',
                'profile',
                'file',
                'filecent',
                'fileaction',
                'fileupload',
                'nl',
                'nllist',
                'nlrecip',
                'nlabo'
            );

            foreach ($newgroupnames as $groupname) {
                //if sys group is not existing we add this new group
                if (!_dbQuery('SELECT `group_syskey` FROM `'.DB_PREPEND.'phpwcms_usergroup` WHERE `group_syskey` = '._dbEscape($groupname))) {
                    $data['group_syskey'] = $groupname;
                    _dbInsert(DB_PREPEND.'phpwcms_usergroup', $data);
                }
            }

        }
    }
    // end

    return $status;
}
