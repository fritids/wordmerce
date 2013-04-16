<?php

/**
 * @package esendex.lib.authentication
 */

/**
 * Interface for authentication strategies
 */
interface IAuthentication
{
	function serialiseAsHttpHeader();
}