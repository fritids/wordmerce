<?php
/**
 * @package esendex.lib.internal.xml
 */

/**
 * 
 */
class ContactXmlParser
{
	function parseSingleContactXml($xmlString)
	{
		$dom = new DomDocument('1.0', 'UTF-8');
		$dom->loadXML($xmlString);

		$root = $dom->documentElement;

		$contact = $this->parseContact($root);

		return $contact;
	}

	function parseContactsPage($xmlString, &$total = null)
	{
		$dom = new DomDocument('1.0', 'UTF-8');
		$dom->loadXML($xmlString);

		$root = $dom->documentElement;

		$total = $root->getAttribute('total');

		$itemsElement = esx_single_element($root, 'items');

		$contactElements = $itemsElement->getElementsByTagname('contact');

		$contacts = array();
		for ($i = 0; $i < $contactElements->length; $i++)
		{
			$contact = $this->parseContact($contactElements->item($i));
			$contacts[] = $contact;
		}

		return $contacts;
	}

	function deserialiseBatchPut($batchXml)
	{
		$dom = new DomDocument('1.0', 'UTF-8');
		$dom->loadXML($batchXml);
		
		$root = $dom->documentElement;
		
		$items = esx_single_element($root, 'items');
		$itemElements = $items->getElementsByTagname('item');
		
		$resultItems = array();
		for ($i = 0; $i < $itemElements->length; $i++)
		{
			$resultItems[] = $this->deserialiseResultItem($itemElements->item($i));
		}

		return $resultItems;
	}

	/**
	 * Deserialise a single item in a results xml document
	 *
	 * @param DomElement $resultElement
	 * @return OperationResultItem
	 */
	function deserialiseResultItem(DomElement $resultElement)
	{
		$uri = null;
		if($resultElement->hasAttribute('uri'))
		{
			$uri = $resultElement->getAttribute('uri');
		}

		$operationElement = esx_single_element($resultElement, 'operation');

		$resultItem = new OperationResultItem (
			$operationElement->getAttribute('type'),
			$operationElement->getAttribute('outcome'),
			RestContactService::CONTACT_SERVICE,
			$uri,
			$resultElement->nodeValue
		);
		
		return $resultItem;
	}

	function serialiseContact(Contact $contact)
	{
		$dom = new DomDocument('1.0', 'UTF-8');

		$contactElement = $this->serialiseContactToDomElement($contact, $dom);
		$dom->appendChild($contactElement);

		return $dom->saveXML();
	}

	function serialiseContactArray(array $contacts)
	{
		$dom = new DomDocument('1.0', 'UTF-8');
		$root = $dom->createElement("contacts");

		foreach($contacts as $contact)
		{
			$root->appendChild($this->serialiseContactToDomElement($contact, $dom));
		}

		$dom->appendChild($root);

		return $dom->saveXML(); 
	}

	function serialiseContactToDomElement(Contact $contact, DomDocument $dom)
	{
		$root = $dom->createElement("contact");
		
		if($contact->id() != null && $contact->id() != '')
		{
			$root->setAttribute('id', $contact->id());
		}
		
		esx_append_if_not_empty($root, $dom->createElement('quickname', $contact->quickname()));
		esx_append_if_not_empty($root, $dom->createElement('firstname', $contact->firstname()));
		esx_append_if_not_empty($root, $dom->createElement('lastname', $contact->lastname()));
		esx_append_if_not_empty($root, $dom->createElement('mobilenumber', $contact->mobilenumber()));

		return $root;
	}

	function parseContact(DomElement $contactElement)
	{
		$contact = new Contact();
		$elements = esx_element_array( $contactElement->childNodes );

		$contactId = $contactElement->getAttribute('id');

		$contact->id($contactId);

		$contact->quickname(esx_array_get('quickname', $elements));
		$contact->firstname(esx_array_get('firstname', $elements));
		$contact->lastname(esx_array_get('lastname', $elements));

		$contact->mobileNumber(esx_array_get('mobilenumber', $elements));
		$contact->telephoneNumber(esx_array_get('telephonenumber', $elements));
		$contact->emailAddress(esx_array_get('emailaddress', $elements));

		$address = $this->parseAddress(esx_single_element($contactElement, 'address'));

		$contact->address($address);

		return $contact;
	}

	function parseAddress(DomElement $addressElement)
	{
		$address = new Address();

		$elements = esx_element_array( $addressElement->childNodes );

		$address->street1(esx_array_get('street1', $elements));
		$address->street2(esx_array_get('street2', $elements));
		$address->town(esx_array_get('town', $elements));
		$address->county(esx_array_get('county', $elements));
		$address->country(esx_array_get('country', $elements));
		$address->postcode(esx_array_get('postcode', $elements));

		return $address;
	}
}