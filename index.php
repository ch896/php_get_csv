<?php
/*
	文字コード判別・変換してCSVデータを扱う
	mb_convert_xxxx は最適化されてるのか重くない。
*/

define('FILE_NAME', './test_sjis.csv');
define('TO_ENCODE', 'UTF-8');

// データの文字コードを変換する
$fp = new SplFileObject(FILE_NAME);
list($encResult, $fromEncode) = getEncode($fp->fgets());
if (!$encResult) {
	echo 'error : 文字コードが判別できません -> ' . $fromEncode;
	exit;
}

// 変換して保存
$fp->setFlags(SplFileObject::READ_CSV);
foreach ($fp as $line) {
	mb_convert_variables(TO_ENCODE, $fromEncode, $line);
	$records[] = $line;
}

var_dump($records);

// CSV の文字コードを判別する
function getEncode($str)
{
	foreach (['UTF-8', 'SJIS', 'EUC-JP', 'ASCII', 'JIS'] as $key) {
		if (mb_convert_encoding($str, $key, $key) == $str) return [true, $key];
	}
	return [false, $str];
}

?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>Get Csv</title>
	</head>
	<body>
		<p>PHP で CSV ファイルを開いて文字コード変換してから取得する</p>
	</body>
</html>
