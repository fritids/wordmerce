<?php

/**
 * @package esendex.rest
 */

/**
 * Create, modify and delete contacts
 */
class RestContactService extends RestBaseService
{
	const CONTACT_SERVICE_VERSION = 'v0.3';
	const CONTACT_SINGLE_SERVICE = 'contact';
	const CONTACT_MULTIPLE_SERVICE = 'contacts';
	const CONTACT_SERVICE = 'contacts';
	
	private $contactsParser;
	
	function __construct(IAuthentication $authentication, $isSecure = false, $certificate = '')
	{
		parent::__construct($authentication, $isSecure, $certificate);
		
		$this->contactsParser = new ContactXmlParser();
	}
	
	/**
	 * Return a single contact by using its ID
	 *
	 * @param string $id Unique identifier in the following format 00000000-0000-0000-0000-000000000000
	 */
	function getContactById($id)
	{
		$uri = $this->buildContactsUri(false, $id);
		
		$result = $this->httpUtil->get( $uri, $this->authentication, $this->port() );
		
		$contact = $this->contactsParser->parseSingleContactXml($result);
		
		return $contact;
	}
	
	/**
	 * Return a single contact by using its quick name
	 *
	 * @param string $quickname
	 * @return Contact
	 */
	function getContactByQuickName($quickname)
	{
		return $this->getContactById($quickname);
	}
	
	/**
	 * Retrieve a contact through a ResultItem
	 *
	 * @param ResultItem $resultItem
	 * @return Contact
	 */
	function getContactByResult(OperationResultItem $resultItem)
	{
		if($resultItem->service() != self::CONTACT_SERVICE)
		{
			throw new ArgumentException('the result item was not a result from this service');
		}
		else if($resultItem->uri() == null || $resultItem->uri() == '')
		{
			throw new ArgumentException('the result items URI is empty');
		}
		
		$result = $this->httpUtil->get(
			$resultItem->uri(), $this->authentication, $this->port()
		);
		
		$contact = $this->contactsParser->parseSingleContactXml($result);
		
		return $contact;
	}
	
	/**
	 * Get a 'page' of contacts. 
	 * 
	 * see documentation for more on paging.
	 * 
	 * @param int $page The page number of contacts you want to retrieve
	 * @param int $totalContacts optional pass by reference, this will return the total number of contacts the account has
	 * @return array
	 */
	function getContacts($page, &$totalContacts = null)
	{
		if(!is_int($page))
		{
			throw new ArgumentException('parameter $page is not an int');
		}
		
		$uri = "{$this->buildContactsUri(true, $id)}?page=$page";
		
		$result = $this->httpUtil->get($uri, $this->authentication, $this->port());
		
		$contacts = $this->contactsParser->parseContactsPage($result, $totalContacts);
		
		return $contacts;
	}
	
	/**
	 * Create a single contact
	 *
	 * @param Contact $contact
	 * @return OperationResultItem
	 */
	function createContact(Contact $contact)
	{
		$uri = $this->buildContactsUri(false);
		
		$contactXml = $this->contactsParser->serialiseContact($contact);
		
		esx_debug("creating contact {$contact->quickname()}");
		
		$result = $this->httpUtil->put(
			$uri, $this->authentication, $this->port(), $contactXml
		);
		
		$resultArray = $this->contactsParser->deserialiseBatchPut($result);
		
		if(count($resultArray) == 0)
		{
			throw new Exception('no result item was returned');
		}
		
		return $resultArray[0];
	}
	
	/**
	 * Create multiple contacts at once, all the contacts in the array
	 * passed in must not have the same quick name as another contact in
	 * the array or already created in the system.  If any of the contacts
	 * has an id then this method will throw an ArgumentException.
	 *
	 * @param array $contacts array of type Contact
	 * @return ContactsBatchResult
	 */
	function createContacts(array $contacts)
	{
		// contact cannot have an ID to create it
		foreach($contacts as $contact)
		{
			if($contact->id() != null || $contact->id() != '')
			{
				throw new ArgumentException('to create a contact it cannot have an ID');
			}
		}
		
		return $this->createUpdateContacts($contacts);		
	}
	
	/**
	 * Update one contact, the contact must have its id field set with
	 * the id of a pre existing contact.  If no id is set then this method 
	 * will throw an ArgumentException.  Any other fields that are set will 
	 * overwrite the fields of the contact stored in the system.
	 *
	 * @param Contact $contact
	 * @return ResultItem
	 */
	function updateContact(Contact $contact)
	{
		if($contact->id() == null || $contact->id() == '')
		{
			throw new ArgumentException('A contact must have an ID to be updated');
		}
		
		return $this->createUpdateContact($contact);
	}
	
	/**
	 * Update multiple contacts.  This method takes in an array of Contact
	 * instances.
	 *
	 * @param array $contacts
	 * @return ContactsBatchResult
	 */
	function updateContacts(array $contacts)
	{
		// make sure that all contacts have an their id property set, if they do not then they will be created
		// to force contacts are only updated
		foreach($contacts as $contact)
		{
			if($contact->id() == null || $contact->id() == '')
			{
				throw new ArgumentException('to update a contact it must have an ID');
			}
		}
		
		return $this->createUpdateContacts($contacts);
	}
	
	/**
	 * Contacts can be updated and created in the same method call. If a
	 * contact has no id then the API will try to create it and if the id is
	 * set then it will assume that the contact is to be updated with the 
	 * fields given.
	 *
	 * @param array $contacts
	 * @return ContactsBatchResult
	 */
	function createUpdateContacts(array $contacts)
	{
		$uri = $this->buildContactsUri(true);
		
		$contactXml = $this->contactsParser->serialiseContactArray($contacts);
		
		$result = $this->httpUtil->put( 
			$uri, $this->authentication, $this->port(), $contactXml 
		);
		
		$resultItemArray = $this->contactsParser->deserialiseBatchPut($result);
		
		return new ContactsBatchResult($resultItemArray, $contacts);	
	}

	/**
	 * Create or update a contact, no validation is made on the contact.
	 *
	 * @param Contact $contact
	 * @return ResultItem
	 */
	function createUpdateContact(Contact $contact)
	{
		$uri = $this->buildContactsUri(false);
		
		$contactXml = $this->contactsParser->serialiseContact($contact);
		
		$result = $this->httpUtil->put( 
			$uri, $this->authentication, $this->port(), $contactXml 
		);
		
		$resultArray = $this->contactsParser->deserialiseBatchPut($result);
		
		return $resultArray[0];
	}
	
	/**
	 * Delete a contact
	 *
	 * @param mixed $contact
	 * @return boolean
	 */
	function delete($contact)
	{
		$contactref = $this->getContactReference($contact);
		
		esx_debug("deleting contact {$contactref}");
		
		$uri = $this->buildContactsUri(false, $contactref);
		
		$httpStatusCode = $this->httpUtil->delete( 
			$uri, $this->authentication, $this->port() 
		);
		
		return $httpStatusCode == 200;
	}
	
	/**
	 * Return the URI for the service
	 *
	 * @param string true if 'contacts', false if 'contact'
	 * @param string optional id or quickname of the contact
	 * @return unknown
	 */
	function buildContactsUri($multiple, $contactReference = null)
	{
		$contactServiceName = RestContactService::CONTACT_MULTIPLE_SERVICE;
		
		if( $multiple == false )
		{
			$contactServiceName = RestContactService::CONTACT_SINGLE_SERVICE;
		}
		
		$uri = $this->serviceUri ( 
			$contactServiceName, 
			RestContactService::CONTACT_SERVICE_VERSION,
			$this->authentication->accountReference()
		);
		
		if($contactReference != null)
		{
			$uri = "{$uri}/{$contactReference}";
		}
		
		return $uri;
	}
	
	/**
	 * Return a contact reference from either a contact or a string. A contact
	 * reference is either its id or its quick name which are both unique.
	 * 
	 *
	 * @param mixed $contact
	 * @return string
	 */
	function getContactReference($contact)
	{
		$contactref = '';
		
		if($contact instanceof Contact)
		{
			if($contact->id() != null && $contact->id() != '')
			{
				$contactref = $contact->id();
			}
			else if($contact->quickname() != null && $contact->quickname() != '')
			{
				$contactref = $contact->quickname();
			}
			else
			{
				throw new ArgumentException('the contact to be deleted does not have an ID or quick name');
			}
		}
		else if(is_string($contact))
		{
			$contactref = $contact;
		}
		else
		{
			throw new ArguementException("the argument contact is not of a valid type");
		}
		
		return $contactref;
	}
}