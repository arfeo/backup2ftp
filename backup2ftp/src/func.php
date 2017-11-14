<?

// -------------------------------------------------------------------------
// Получение списка файлов бэкапа
// -------------------------------------------------------------------------
function scan_backup_dir() {

	$dir = __DIR__ . "/../../../backup";
	$files = Array();
	$scan = scandir($dir);

	foreach($scan as $file) {
		
		if(strpos($file, "tar.gz") !== false) {

			$files[] = $file;

		}

	}

	return $files;

}

// -------------------------------------------------------------------------
// Получение списка файлов бэкапа
//
// @param {Resource} ftp 			Идентификатор соединения с FTP сервером
// @param {Array} files 			Массив с именами файлов
// @param {String} log 				Путь к файлу лога
// -------------------------------------------------------------------------
function copy_files($ftp, $files, $log) {

	ftp_pasv($ftp, true);

	foreach($files as $file) {
		
		if(ftp_put($ftp, $_GET["dir"] . $file, __DIR__ . "/../../../backup/" . $file, FTP_BINARY)) {

			file_put_contents($log, date("d-m-Y H:i:s") . ";" . basename($file) . " успешно загружен на сервер\n", LOCK_EX | FILE_APPEND);

		} else {

			file_put_contents($log, date("d-m-Y H:i:s") . ";" . "Не удалось загрузить " . basename($file) . " на сервер\n", LOCK_EX | FILE_APPEND);

		}

	}

}