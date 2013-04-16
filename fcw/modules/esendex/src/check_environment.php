<?php
/**
 * @package esendex
 */

$curlLoaded = extension_loaded('curl');
$domLoaded = extension_loaded('dom');

if($curlLoaded && $domLoaded)
{
	echo 'All extensions neede by the Esendex SDK are loaded';
}
else
{
	if(!$curlLoaded)
	{
		echo 'Curl is not loaded!  ';
	}
	
	if(!$domLoaded)
	{
		echo 'dom  is not loaded!  ';
	}
}