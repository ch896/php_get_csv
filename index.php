<?php
/*
	文字コード判別・変換してCSVデータを扱う
	※ 文字コード変換する必要ないのが分かってる場合は SplFileObject 使った方が速い
*/

define('FILE_NAME', './test_sjis.csv');
define('TO_ENCODE', 'UTF-8');

// データの文字コードを変換する
$buf = file_get_contents(FILE_NAME);
$tmp = mb_substr($buf, 0, mb_strpos($buf, "\n"));
$from = getEncode($tmp);
if ($from == null)
{
	echo 'error : 文字コードが判別できません -> ' . $tmp;
	exit;
}
$encoded = $from == TO_ENCODE ? $buf : mb_convert_encoding($buf, TO_ENCODE, $from);

// 分割して格納
$lines = explode("\n", $encoded);
foreach ($lines as $line)
{
	if (!mb_strlen($line)) continue;

	$line = str_replace("\r", "", $line);
	$records[] = explode(",", $line);
}

var_dump($records);

// CSV の文字コードを判別する
function getEncode($str)
{
	foreach (['UTF-8', 'SJIS', 'EUC-JP', 'ASCII', 'JIS'] as $key)
	{
		if (mb_convert_encoding($str, $key, $key) == $str) return $key;
	}
	return null;
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
