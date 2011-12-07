<?php
$simple = simplexml_load_file('browsers.xml');
$browserList = '';

$browserList .= '<?php' . "\n";
$browserList .= 'class BrowserList {' . "\n";
$browserList .= "\t" . 'public static function getBrowsers() {' . "\n";
$browserList .= "\t\t" . '$browsers = array();' . "\n";

for($i = 0; $i < count($simple->Browser); $i++) {
	//print_r($simple->Browser[$i]);
	$patterns = array();
	$tags = array();

	for($j = 0; $j < count($simple->Browser[$i]->Patterns->Pattern); $j++) {
		$patterns[] = '\'' . $simple->Browser[$i]->Patterns->Pattern[$j] . '\'';
	}

	for($j = 0; $j < count($simple->Browser[$i]->Tags->Tag); $j++) {
		$tags[] = '\'' . $simple->Browser[$i]->Tags->Tag[$j] . '\'';
	}

	$browserList .= "\t\t" . '$browsers[] = new BrowserUA(\'' . $simple->Browser[$i]->attributes()->code . '\', \'' . $simple->Browser[$i]->Name . '\', array(' . join(',', $patterns) . '), array(' . join(',', $tags) . '));' . "\n";


}

$browserList .= "\t\t" . 'return $browsers;' . "\n";
$browserList .= "\t" . '}' . "\n";
$browserList .= '}' . "\n";
$browserList .= '?>';

//echo $browserList;
file_put_contents('BrowserList.php', $browserList);
?>