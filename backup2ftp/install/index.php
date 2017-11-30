<?

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class backup2ftp extends CModule {

    public function __construct() {

        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if(is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'backup2ftp';
        $this->MODULE_NAME = Loc::getMessage('BACKUP2FTP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('BACKUP2FTP_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->MODULE_ROOT_DIR = dirname(__DIR__);
        $this->PARTNER_NAME = Loc::getMessage('BACKUP2FTP_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://spsbk.ru';

    }

    public function doInstall() {

        if($this->installFiles()) {

            ModuleManager::registerModule($this->MODULE_ID);

            return true;

        }

    }

    public function doUninstall() {

        if($this->uninstallFiles()) {

            ModuleManager::unRegisterModule($this->MODULE_ID);

            return true;

        }

    }

    public function installFiles() {

        @mkdir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/css/backup2ftp/", 0750, true);

        copy($this->MODULE_ROOT_DIR . "/install/admin/backup2ftp_copy.php", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/backup2ftp_copy.php");
        copy($this->MODULE_ROOT_DIR . "/install/admin/backup2ftp_index.php", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/backup2ftp_index.php");
        copy($this->MODULE_ROOT_DIR . "/install/admin/backup2ftp_log.php", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/backup2ftp_log.php");
        copy($this->MODULE_ROOT_DIR . "/src/styles.css", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/css/backup2ftp/styles.css");

        return true;

    }

    public function uninstallFiles() {

        unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/backup2ftp_copy.php");
        unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/backup2ftp_index.php");
        unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/backup2ftp_log.php");
        unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/css/backup2ftp/styles.css");

        @rmdir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/css/backup2ftp/");

        return true;

    }

}
