<?

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'backup2ftp');

if(!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages($context->getServer()->getDocumentRoot() . "/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

$tabControl = new CAdminTabControl("tabControl", array(

    array(
        "DIV" => "b2f_prefs",
        "TAB" => Loc::getMessage("MAIN_TAB_SET"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"),
    ),

));

//// Сохранение настроек ////
if(!empty($save) && $request->isPost() && check_bitrix_sessid()) {

    if($request->getPost('domain') && $request->getPost('port')) {

        Option::set(ADMIN_MODULE_NAME, "domain", $request->getPost('domain'));
        Option::set(ADMIN_MODULE_NAME, "port", $request->getPost('port'));
        Option::set(ADMIN_MODULE_NAME, "dir", $request->getPost('dir'));
        Option::set(ADMIN_MODULE_NAME, "auth", $request->getPost('auth'));
        Option::set(ADMIN_MODULE_NAME, "login", $request->getPost('login'));
        Option::set(ADMIN_MODULE_NAME, "pwd", $request->getPost('pwd'));

        CAdminMessage::showMessage(array(

            "MESSAGE" => Loc::getMessage("REFERENCES_OPTIONS_SAVED"),
            "TYPE" => "OK",

        ));

    } else {

        CAdminMessage::showMessage(Loc::getMessage("REFERENCES_INVALID_VALUE"));

    }

}

$tabControl->begin();
?>
<form method="post" action="<?=sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>">
    <?
      echo bitrix_sessid_post();
      $tabControl->beginNextTab();
    ?>
    <tr>
        <td width="40%">
            <label for="domain">Адрес сервера FTP:</label>
        <td width="60%">
            <input type="text"
                   size="50"
                   maxlength="255"
                   name="domain"
                   value="<?= Option::get(ADMIN_MODULE_NAME, "domain") ?>"
                   />
        </td>
    </tr>
    <tr>
        <td width="40%">
            <label for="port">Порт:</label>
        <td width="60%">
            <input type="text"
                   size="5"
                   maxlength="5"
                   name="port"
                   value="<?= Option::get(ADMIN_MODULE_NAME, "port") ?>"
                   />
        </td>
    </tr>
    <tr>
        <td width="40%">
            <label for="port">Каталог на сервере:</label>
        <td width="60%">
            <input type="text"
                   size="50"
                   maxlength="255"
                   name="dir"
                   value="<?= Option::get(ADMIN_MODULE_NAME, "dir") ?>"
                   />
        </td>
    </tr>
    <tr>
        <td width="40%">
            <label for="auth">Использовать аутентификацию:</label>
        <td width="60%">
            <input type="checkbox"
                   name="auth"
                   <?= (Option::get(ADMIN_MODULE_NAME, "auth") == 'on') ? 'checked="checked"' : ''; ?>
                   />
        </td>
    </tr>
    <tr>
        <td width="40%">
            <label for="login">Логин:</label>
        <td width="60%">
            <input type="text"
                   size="50"
                   maxlength="255"
                   name="login"
                   value="<?= Option::get(ADMIN_MODULE_NAME, "login") ?>"
                   />
        </td>
    </tr>
    <tr>
        <td width="40%">
            <label for="pwd">Пароль:</label>
        <td width="60%">
            <input type="password"
            	   autocomplete="off"
                   size="50"
                   maxlength="255"
                   name="pwd"
                   value="<?= Option::get(ADMIN_MODULE_NAME, "pwd") ?>"
                   />
        </td>
    </tr>
    <?php
      $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
           />
    <?
      $tabControl->end();
    ?>
</form>