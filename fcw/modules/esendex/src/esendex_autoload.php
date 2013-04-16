<?php

/**
 * @package esendex
 * 
 * esendex_autoloader runs all the scripts necessary for loading all required
 * php functions, configuration and classes into memory.
 * 
 */

// ESENDEX_HOME is the path to the root directory if the esendex php sdk
// set this variable to change where the sdk, note that it does need a forward
// slash at the end
if(!defined('ESENDEX_HOME'))
{
	define('ESENDEX_HOME', "");
}

// require the script the sdk needs on each run through
require_once ESENDEX_HOME.'lib/pearlog/Log.php';
require_once ESENDEX_HOME.'lib/Exceptions.php';
require_once ESENDEX_HOME.'lib/internal/esendex_sdk_config.php';
require_once ESENDEX_HOME.'lib/internal/esendex_logging.php';
require_once ESENDEX_HOME.'lib/internal/esendex_xml_utilities.php';

/**
 * esendex_autoload is the class loader for all classes in the SDK
 * 
 * @param string $classname
 */
function esendex_autoload($classname)
{	
	$directories = array (
		ESENDEX_HOME."rest/", 
		ESENDEX_HOME."lib/", 
		ESENDEX_HOME."lib/internal/xml/",
		ESENDEX_HOME."lib/internal/",
		ESENDEX_HOME."lib/authentication/"
	);
	
	foreach($directories as $directory)
    {
    	$path = $directory.$classname.'.php';
    	
        if(file_exists($path))
        {
            require_once($path); 
            return;
        }
    }
}

// register the esendex autoload function in php for this run of the script
spl_autoload_register('esendex_autoload');

?>