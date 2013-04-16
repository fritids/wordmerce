<?php

/**
 * this script contains wrapper functions around the logger
 * 
 *
 * @package esendex.lib.internal
 */

/**
 * returns a logger
 *
 * @return Log
 */
function esx_logger()
{
	return Log::singleton(ESENDEX_LOGGER, LOG_LOCATION, 'EsendexSdk', null, SDK_LOG_LEVEL);
}

function esx_can_log($level)
{
	if(SDK_LOG_LEVEL == PEAR_LOG_ALL)
	{
		return true;
	}
	else if(SDK_LOG_LEVEL <= $level && SDK_LOG_LEVEL != PEAR_LOG_NONE)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function esx_debug($message, array $loginfo = null)
{
	if(esx_can_log(PEAR_LOG_DEBUG))
	{
		$log = esx_logger();
		if($loginfo != null) {
			
			$log->debug($message."\n".var_dump($loginfo));
		}
		else {
			$log->debug($message);
		}
	}
}

function esx_info($message, array $loginfo = null)
{
	if(esx_can_log(PEAR_LOG_INFO))
	{
		$log = esx_logger();
		$log->info($message);
	}
}

function esx_error($message, array $loginfo = null)
{
	if(esx_can_log(PEAR_LOG_EMERG))
	{
		$log = esx_logger();
		$log->err($message);
	}
}

function esx_log_exception($exception)
{
	esx_error( $exception->__toString() );
}

function esx_log_object($object)
{
	esx_info( $object->__toString() );
}

?>