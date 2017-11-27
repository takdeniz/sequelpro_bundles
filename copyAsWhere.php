#!/usr/bin/env php
<?php
$stdIn  = fopen("php://stdin", "r");
//$stdIn  = fopen("input.test", "r");
$result = [];
while ($row = fgetcsv($stdIn, 0,"\t")) {
	array_push($result, $row);
}
$columns = array_shift($result);
if ($result) {
	foreach ($result as $k => $data) {
		$result[$k] = '(' . implode(' and ', condition(array_combine($columns, $data))) . ')';
	}
}

function condition($items)
{
	$result = [];
	foreach ($items as $key => $item) {
		$result[] = '`' . $key . '`=\'' . $item . '\'';
	}

	return $result;
}

$output = 'SELECT * FROM '.$_ENV['SP_SELECTED_TABLE'].' WHERE ' . implode(' OR ', $result);

$cmd = 'echo ' . escapeshellarg($output) . ' | __CF_USER_TEXT_ENCODING=' . posix_getuid() . ':0x8000100:0x8000100 pbcopy';
shell_exec($cmd);
