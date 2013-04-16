<?php

/**
 * @package esendex.lib.internal
 */

/**
 * 
 */
abstract class AbstractMetaClass
{
	/**
	 * overridden magic method allows properties usually accessed by
	 * 
	 * $value = $object->property();
	 * 
	 * can now be accessed by
	 * 
	 * $value = $object->property;
	 *
	 * @param mixed $name
	 * @return mixed
	 */
	function __get($name)
	{
		if(method_exists($this, $name))
		{
			return $this->$name();
		}
		else
		{
			throw new Exception("getter '{$name}' does not exist");
		}
	}
      
	/**
	 * overridden magic method allows properties usually accessed by
	 * 
	 * $object->property($value);
	 * 
	 * can now be accessed by
	 * 
	 * $object->property = $value;
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function __set($name, $value)
	{
		if(method_exists($this, $name))
		{
			return $this->$name($value);
		}
		else
		{
			throw new Exception("setter '{$name}' does not exist");
		}
	}
}