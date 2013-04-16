<?php
/**
 * @package esendex.lib.internal.xml
 */

/**
 * 
 */
class AccountOptionsXmlParser
{
	const API_LONG_MESSAGING = 'apilongmessaging';
	
	/**
	 * Deserialise account options XML to an AccountOptions object
	 *
	 * @param string $xml
	 * @return AccountOptions
	 */
	function deserialiseAccountOptions($xml)
	{
		$dom = new DomDocument('1.0', 'UTF-8');
		$dom->loadXML($xml);
		
		$root = $dom->documentElement;
		
		$options = esx_element_array($root->childNodes);
		
		$accountOptions = new AccountOptions();
		$accountOptions->longMessageingEnabled( parse_bool($options[self::API_LONG_MESSAGING]) );
		
		return $accountOptions;
	}
	
	/**
	 * Serialise an AccountOptions object to XML
	 *
	 * @param AccountOptions $accountOptions
	 * @return string
	 */
	function serialiseAccountOptions(AccountOptions $accountOptions)
	{
		$dom = new DomDocument('1.0', 'UTF-8');
		$root = $dom->createElement("accountoptions");
		
		$longMessageElement = $dom->createElement(
			self::API_LONG_MESSAGING, bool_to_string( $accountOptions->longMessageingEnabled() )
		);
		
		$root->appendChild($longMessageElement);
		$dom->appendChild($root);
		
		return $dom->saveXML(); 
	}
}
