<?php

/**
 * @package esendex.rest
 */

/**
 * The RestContactsChangedService can retrieve a contact's history of modifications
 */
class RestContactsChangedService extends RestBaseService
{
	const CONTACTS_CHANGED_SERVICE = 'contacts/changed';
	const CONTACTS_CHANGED_VERSION = 'v0.3';
	
	const CHANGES_SINCE_QUERY = 'since';
	
	private $contactsChangedParser;
	
	function __construct(IAuthentication $authentication, $isSecure = false, $certificate = '')
	{
		parent::__construct($authentication, $isSecure, $certificate);
		
		$this->contactsChangedParser = new ContactsChangedXmlParser();
	}
	
	/**
	 * Return all the changes to contacts since a certain date.
	 *
	 * @param int $date unix timestamp
	 */
	function getChangedContactsUsingTimeStamp($timeStamp)
	{
		if(!is_int($timeStamp))
		{
			throw new ArgumentException('Unix timestamp must be an int');
		}
		
		return $this->getChangedContacts($timeStamp);
	}
	
	function getChangedContactsUsingDate($year, $month, $day, $hour, $minute, $seconds = null)
	{
		if($seconds == null)
		{
			$seconds = 0;
		}
		
		if(is_int($year) && is_int($month) && is_int($day) && is_int($hour) && is_int($minute))
		{
			$dateString = sprintf("%04d%02d%02dT%02d%02d%02dZ", $year, $month, $day, $hour, $minute, $seconds);
			
			return $this->getChangedContacts($dateString);
		}
		else
		{
			throw new ArgumentException('parameters must be of type int');
		}
	}
	
	function getChangedContacts($date)
	{
		$uri = $this->serviceUri(
			self::CONTACTS_CHANGED_SERVICE, self::CONTACTS_CHANGED_VERSION, $this->authentication->accountReference()
		);
		
		$uri = "{$uri}?".self::CHANGES_SINCE_QUERY."={$date}";
		
		$resultString = $this->httpUtil->get(
			$uri, $this->authentication, $this->port()
		);
		
		$resultList = $this->contactsChangedParser->deserialiseContactsChanged($resultString);
		
		return $resultList;
	}
}