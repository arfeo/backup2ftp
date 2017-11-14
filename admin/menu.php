<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if($APPLICATION->GetGroupRight("backup2ftp") > "D") {

	if(!CModule::IncludeModule('backup2ftp'))
		return false;

	$menu = array(

	    array(

	        'parent_menu' => 'global_menu_services',
	        'sort' => 400,
	        'text' => Loc::getMessage('BACKUP2FTP_MENU_TITLE'),
	        'title' => Loc::getMessage('BACKUP2FTP_MENU_TITLE'),
	        'items_id' => 'menu_references',
	        'items' => array(

	            array(

	                'text' => Loc::getMessage('BACKUP2FTP_COPY_MENU_TITLE'),
	                'url' => '/bitrix/admin/backup2ftp_index.php?lang=' . LANGUAGE_ID,
	                'more_url' => array('/bitrix/admin/backup2ftp_index.php?lang=' . LANGUAGE_ID),
	                'title' => Loc::getMessage('BACKUP2FTP_COPY_MENU_TITLE'),

	            ),

	            array(

	                'text' => Loc::getMessage('BACKUP2FTP_LOG_MENU_TITLE'),
	                'url' => '/bitrix/admin/backup2ftp_log.php?lang=' . LANGUAGE_ID,
	                'more_url' => array('/bitrix/admin/backup2ftp_log.php?lang=' . LANGUAGE_ID),
	                'title' => Loc::getMessage('BACKUP2FTP_LOG_MENU_TITLE'),

	            ),

	        ),

	    ),

	);

	return $menu;

}

return false;