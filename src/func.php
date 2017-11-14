<?

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