<?php	
/**
 * @package esendex.lib.internal
 */

    /**
     * create a key value pair array from a DomNodeList
     */
    function esx_element_array(DomNodeList $nodelist)
    {
        $dic = array();
        $i = 0;

        // build the array
        while( ($node = $nodelist->item($i)) != null )
        {
            $i++;
            $dic[$node->nodeName] = $node->nodeValue;
        }

        return $dic;
    }
    
    /**
     * Retrieves a single child element from the node given with
     * the name of the $elementName parameter
     *
     * @param DomElement $node
     * @param string $elementName
     * @return DomNode
     */
    function esx_single_element($node, $elementName)
    {
    	if($node == null)
    		throw new ArgumentException('esx_single_element() $node cannot be null');
    		
    	$nodelist = $node->getElementsByTagName($elementName);
    	
    	if($nodelist->length == 1)
        {
        	return $nodelist->item(0);
        }
        else
        {
        	throw new XmlException("failed to retrieve element '{$elementName}' from node '".$node->tagName."'", 0);
        }
    }
    
    /**
     * Appends $childElement to $element if the nodeValue of $childElement is not
     * null or empty.
     *
     * @param DOMElement $element
     * @param DOMElement $childElement
     */
    function esx_append_if_not_empty(DOMElement $element, DOMElement $childElement)
    {
    	if($childElement->nodeValue != null && $childElement->nodeValue != '')
    	{
    		$element->appendChild($childElement);    		
    	}
    }
    
    /**
     * Returns the value to the $key or null if the key does
     * not exist in $array
     *
     * @param mixed $key
     * @param array $array
     * @return mixed
     */
    function esx_array_get($key, array $array)
    {
    	if(key_exists($key, $array))
    	{
    		return $array[$key];
    	}
    	return null;
    }
    
    /**
     * Return the last 'fragment' of a url
     *
     * @param string $url
     * @return string
     */
    function esx_get_last_url_fragment($url)
    {
    	$fragments = split('/', $url);
    	
    	end($fragments);
    	
    	return current($fragments);
    }

    /**
 * parse a string to a boolean
 *
 * @param string $string
 */
function parse_bool($string)
{
	if(strtolower($string) == 'true')
	{
		return true;
	}
	return false;
}

/**
 * Parse a boolean to a string as either 'true' or 'false'
 *
 * @param boolean $bool
 * @return string
 */
function bool_to_string($bool)
{
	if($bool)
	{
		return 'true';
	}
	else
	{
		return 'false';
	}
}