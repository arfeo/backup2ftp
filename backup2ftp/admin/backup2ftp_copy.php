<?

define('APP_TMP_DIR', '/var/tmp/');

$log = APP_TMP_DIR . "backup2ftp.log";
$stamp = date("d-m-Y H:i:s");
$div = ";";

if(isset($_GET["files"]) && !empty($_GET["files"])) {

	$files = explode(",", $_GET["files"]);

	// Установка соединения
	$ftp = ftp_connect($_GET["domain"], $_GET["port"]);

	if($ftp !== false) {

		if($_GET["auth"] == 'on') {

			// Проверка имени пользователя и пароля
			$login_result = ftp_login($ftp, $_GET["login"], $_GET["pwd"]);

			if($login_result === true) {

				ftp_pasv($ftp, true);

				foreach($files as $file) {
					
					if(ftp_put($ftp, $_GET["dir"] . $file, __DIR__ . "/../../../backup/" . $file, FTP_BINARY)) {

						file_put_contents($log, $stamp . $div . basename($file) . " успешно загружен на сервер (" . $_GET["domain"] . ":" . $_GET["port"] . ")\n", LOCK_EX | FILE_APPEND);

					} else {

						file_put_contents($log, $stamp . $div . "Не удалось загрузить " . basename($file) . " на сервер (" . $_GET["domain"] . ":" . $_GET["port"] . ")\n", LOCK_EX | FILE_APPEND);

					}

				}

			} else {

				file_put_contents($log, $stamp . $div . "Невозможно соединиться с сервером " . $_GET["domain"] . ":" . $_GET["port"] . "\n", LOCK_EX | FILE_APPEND);

			}

		} else {

			ftp_pasv($ftp, true);

			foreach($files as $file) {
					
				if(ftp_put($ftp, $_GET["dir"] . $file, __DIR__ . "/../../../backup/" . $file, FTP_BINARY)) {

					file_put_contents($log, $stamp . $div . basename($file) . " успешно загружен на сервер (" . $_GET["domain"] . ":" . $_GET["port"] . ")\n", LOCK_EX | FILE_APPEND);

				} else {

					file_put_contents($log, $stamp . $div . "Не удалось загрузить " . basename($file) . " на сервер (" . $_GET["domain"] . ":" . $_GET["port"] . ")\n", LOCK_EX | FILE_APPEND);

				}

			}

		}

		// Закрытие соединения
		ftp_close($ftp);

	} else {

		file_put_contents($log, $stamp . $div . "Невозможно соединиться с сервером " . $_GET["domain"] . ":" . $_GET["port"] . "\n", LOCK_EX | FILE_APPEND);

	}

} else {

	file_put_contents($log, $stamp . $div . "Не указаны файлы для копирования\n", LOCK_EX | FILE_APPEND);

}

exit;