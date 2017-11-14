<?

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'backup2ftp');

require_once $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_before.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_after.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/backup2ftp/src/func.php";

$APPLICATION->SetTitle("Копирование");

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();
$files = scan_backup_dir();
$back = "?lang=ru";

//// Запуск копирования ////
if($request->get('start_copy') && $request->get('start_copy') == 'true' && $request->get('files')) {

	$files = $request->get('files');

	// Установка соединения
	$ftp = ftp_connect(Option::get(ADMIN_MODULE_NAME, "domain"), Option::get(ADMIN_MODULE_NAME, "port"));

	if($ftp !== false) {

		if(Option::get(ADMIN_MODULE_NAME, "auth") == 'on') {

			// Проверка имени пользователя и пароля
			$login_result = ftp_login($ftp, Option::get(ADMIN_MODULE_NAME, "login"), Option::get(ADMIN_MODULE_NAME, "pwd"));

			if($login_result === true) {

				exec(

					"php-cgi -f " . __DIR__ . "/backup2ftp_copy.php "
					. "domain=" . Option::get(ADMIN_MODULE_NAME, "domain") . " "
					. "port=" . Option::get(ADMIN_MODULE_NAME, "port") . " "
					. "dir=" . Option::get(ADMIN_MODULE_NAME, "dir") . " "
					. "files=" . $files . " "
					. "auth=" . Option::get(ADMIN_MODULE_NAME, "auth") . " "
					. "login=" . Option::get(ADMIN_MODULE_NAME, "login") . " "
					. "pwd=" . Option::get(ADMIN_MODULE_NAME, "pwd") . " "
					. "> /dev/null 2>&1 &"

				);

				// Выставляем флаг с результатом выполнения операции
				setcookie("backup2ftp_status", "ok", time() + 3600);

				header("Location: $back");

			} else {

				// Выставляем флаг с результатом выполнения операции
				setcookie("backup2ftp_status", "error", time() + 3600);

				header("Location: $back");

			}

		} else {

			exec(

				"php-cgi -f " . __DIR__ . "/backup2ftp_copy.php "
				. "domain=" . Option::get(ADMIN_MODULE_NAME, "domain") . " "
				. "port=" . Option::get(ADMIN_MODULE_NAME, "port") . " "
				. "dir=" . Option::get(ADMIN_MODULE_NAME, "dir") . " "
				. "files=" . $files . " "
				. "auth=" . Option::get(ADMIN_MODULE_NAME, "auth") . " "
				. "> /dev/null 2>&1 &"

			);

			// Выставляем флаг с результатом выполнения операции
			setcookie("backup2ftp_status", "ok", time() + 3600);

			header("Location: $back");

		}

		// Закрытие соединения
		ftp_close($ftp);

	} else {

		// Выставляем флаг с результатом выполнения операции
		setcookie("backup2ftp_status", "error", time() + 3600);

		header("Location: $back");

	}

} else {

	if(isset($_COOKIE["backup2ftp_status"])) {

		switch($_COOKIE["backup2ftp_status"]) {

			case 'ok':
				
				CAdminMessage::showMessage(array(

		            "MESSAGE" => "Копирование файлов успешно запущено.",
		            "TYPE" => "OK",

		        ));

				break;
			
			case 'error':
				
				CAdminMessage::showMessage("Невозможно соединиться с сервером FTP. Проверьте настройки.");

				break;

		}

		// Удаляем флаг с результатом выполнения операции
		setcookie("backup2ftp_status", "", time() - 3600);

	}

?>

<p><strong>Выбрать файлы для копирования:</strong></p>
<div style="background-color:#fff; min-height:300px; max-height:300px; overflow:auto; border:1px solid #aaa; margin-bottom:10px">
<table cellpadding="0" cellspacing="0" width="100%">

<?

$y = 0;

foreach($files as $file) {

	$y++;

	echo '<tr style="background-color:#' . (($y % 2 == 0) ? 'f0f0f0' : 'fff') . ';"><td width="5%" style="padding:5px;"><input type="checkbox" xchecked="checked" class="files" id="' . $file . '" name="' . $file . '"></td><td width="60%" style="padding:5px;"><label for="' . $file . '">' . $file . '</label></td><td width="35%" style="padding:5px;">' . round(filesize($_SERVER['DOCUMENT_ROOT'] . "/bitrix/backup/" . $file) / 1024 / 1024, 2) . ' Мб</td></tr>';

}

?>

</table>
</div>

<input type="button" value="Запустить копирование" onClick="startFileCopy();" />
<table style="margin-top:10px">
	<tr><td>Сервер:</td><td><strong><?= Option::get(ADMIN_MODULE_NAME, "domain") ?></strong></td></tr>
	<tr><td>Порт:</td><td><strong><?= Option::get(ADMIN_MODULE_NAME, "port") ?></strong></td></tr>
	<tr><td>Каталог:</td><td><strong><?= Option::get(ADMIN_MODULE_NAME, "dir") ?></strong></td></tr>
	<tr><td colspan="2"><a href="/bitrix/admin/settings.php?lang=ru&mid=backup2ftp">Изменить настройки</a></td></tr>
</table>

<script type="text/javascript">

	function startFileCopy() {

		var 	inputs = document.getElementsByClassName('files'),
		    	l = inputs.length,
		    	i,
		    	files = [];

		for(i = 0; i < l; i++) {

			if(inputs[i].checked) files.push(inputs[i].getAttribute('id'));

		}

		if(files.length === 0) {

			alert('Выберите файлы.');

			return false;

		}

		window.location = window.location.href + '&start_copy=true' + '&files=' + files.join(',');

	}

</script>

<?

}

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';