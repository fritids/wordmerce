<?php

/**
 * @package esendex.lib.internal
 */

/**
 * This is the minimum level the SDK will log at.  For example, if this value
 * was set to PEAR_LOG_NOTICE then PEAR_LOG_INFO and PEAR_LOG_DEBUG messages 
 * will not be logged.  Constants can be found in pearlog/Log.php
 */
define('SDK_LOG_LEVEL', PEAR_LOG_NONE);

/**
 * path to the esendex log file
 */
define('LOG_LOCATION', './logs/out.txt');

/**
 * type of logger used for esendex logging, tryout...
 * - firebug
 * - console
 * - file
 */
define('ESENDEX_LOGGER', 'file');

/**
 * Default version services will use.
 */
define('REST_DEFAULT_VERSION', 'v0.2');

/**
 * The version string of the message header service which will be used in the service url.
 */
define('REST_MESSAGE_HEADER_VERSION', 'v0.2');

/**
 * The version string of the message body service which will be used in the service url.
 */
define('REST_MESSAGE_BODY_VERSION', 'v0.2');

/**
 * The version of the inbox service being used
 */
define('REST_INBOX_VERSION', 'v0.2');

/**
 * The version of the access check service being used
 */
define('REST_ACCESS_CHECK_VERSION', 'v0.2');

/**
 * The domain of the Esendex API Services
 */
define('REST_ESENDEX_API_DOMAIN', 'api.esendex.com');

/**
 * Port for none
 */
define('ESENDEX_API_PORT', 80);

/**
 * URL mock fragment
 */
define('REST_MOCK_KEYWORD', 'mock');

/**
 * Name of the SDK
 */
define('ESENDEX_SDK_NAME', 'cronus:0.3.0');

/**
 * The string format used for date/time timestamps
 */
define('ESENDEX_DATE_TIME_FORMAT', 'Ymd\THis\Z');

?>
