<?php

/**
 * @package esendex.lib
 */

/**
 * Describes a geographical address
 */
class Address extends AbstractMetaClass
{
	private $street1;
	private $street2;
	private $town;
	private $county;
	private $country;
	private $postcode;
	
	function __construct()
	{
		
	}
	
	function street1($street1 = null) {
		if($street1 != null){
			$this->street1 = $street1;
		}
		return $this->street1; 
	}
	
	function street2($street2 = null) {
		if($street2 != null){
			$this->street2 = $street2;
		}
		return $this->street2; 
	}
	
	function town($town = null) {
		if($town != null){
			$this->town = $town;
		}
		return $this->town; 
	}
	
	function county($county = null) {
		if($county != null){
			$this->county = $county;
		}
		return $this->county; 
	}
	
	function country($country = null) {
		if($country != null){
			$this->country = $country;
		}
		return $this->country; 
	}
	
	function postcode($postcode = null) {
		if($postcode != null){
			$this->postcode = $postcode;
		}
		return $this->postcode; 
	}
}