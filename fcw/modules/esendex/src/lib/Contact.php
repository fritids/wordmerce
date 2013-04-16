<?php

/**
 * @package esendex.lib
 */

/**
 * An Esendex contact
 */
class Contact extends AbstractMetaClass
{
	private $id;
	private $quickname;
	private $firstname;
	private $lastname;
	private $mobileNumber;
	private $telephoneNumber;
	private $emailAddress;
	private $address;
	
	function __construct($_quickname = null, $_mobilenumber = null)
	{
		$this->quickname($_quickname);
		$this->mobileNumber($_mobilenumber);
	}
	
	function id($id = null) {
		if($id != null){
			$this->id = $id;
		}
		return $this->id; 
	}
	
	function quickname($quickname = null) {
		if($quickname != null){
			$this->quickname = $quickname;
		}
		return $this->quickname; 
	}
	
	function firstname($firstname = null) {
		if($firstname != null){
			$this->firstname = $firstname;
		}
		return $this->firstname; 
	}
	
	function lastname($lastname = null) {
		if($lastname != null){
			$this->lastname = $lastname;
		}
		return $this->lastname; 
	}
	
	function mobileNumber($mobileNumber = null) {
		if($mobileNumber != null){
			$this->mobileNumber = $mobileNumber;
		}
		return $this->mobileNumber; 
	}
	
	function telephoneNumber($telephoneNumber = null) {
		if($telephoneNumber != null){
			$this->telephoneNumber = $telephoneNumber;
		}
		return $this->telephoneNumber; 
	}
	
	function emailAddress($emailAddress = null) {
		if($emailAddress != null){
			$this->emailAddress = $emailAddress;
		}
		return $this->emailAddress; 
	}
	
	function address(Address $address = null) {
		if($address != null){
			$this->address = $address;
		}
		return $this->address; 
	}
}