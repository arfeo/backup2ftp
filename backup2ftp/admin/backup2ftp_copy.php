<?

define('APP_TMP_DIR', '/var/tmp/');

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../../..");

require_once $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/backup2ftp/src/functions.php";

$log = APP_TMP_DIR . "backup2ftp.log";

if(isset($_GET["files"]) && !empty($_GET["files"])) {

	file_put_contents($log, date("d-m-Y H:i:s") . ";" . "Запуск копирования файлов (сервер " . $_GET["domain"] . ":" . $_GET["port"] . ")\n", LOCK_EX | FILE_APPEND);

	$files = explode(",", $_GET["files"]);

	// Установка соединения
	$ftp = ftp_connect($_GET["domain"], $_GET["port"]);

	if($ftp !== false) {

		if($_GET["auth"] == 'on') {

			// Проверка имени пользователя и пароля
			$login_result = ftp_login($ftp, $_GET["login"], $_GET["pwd"]);

			if($login_result === true) {

				copy_files($ftp, $files, $log);

			} else {

				file_put_contents($log, date("d-m-Y H:i:s") . ";" . "Ошибка авторизации\n", LOCK_EX | FILE_APPEND);

			}

		} else {

			// Проверяем возможность подключения к анонимному серверу
			$login_result = ftp_login($ftp, "anonymous", "");

			if($login_result === true) {

				copy_files($ftp, $files, $log);

			} else {

				file_put_contents($log, date("d-m-Y H:i:s") . ";" . "Ошибка авторизации\n", LOCK_EX | FILE_APPEND);

			}

		}

		// Закрытие соединения
		ftp_close($ftp);

		file_put_contents($log, date("d-m-Y H:i:s") . ";" . "Завершение копирования файлов\n", LOCK_EX | FILE_APPEND);

	} else {

		file_put_contents($log, date("d-m-Y H:i:s") . ";" . "Невозможно соединиться с сервером\n", LOCK_EX | FILE_APPEND);

	}

} else {

	file_put_contents($log, date("d-m-Y H:i:s") . ";" . "Не указаны файлы для копирования\n", LOCK_EX | FILE_APPEND);

}

exit;