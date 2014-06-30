<?php
/**
 *  @class: InputFilter (PHP4 & PHP5, with comments)
 * @project: PHP Input Filter
 * @date: 10-05-2005
 * @version: 1.2.2_php4/php5
 * @author: Daniel Morris
 * @contributors: Gianpaolo Racca, Ghislain Picard, Marco Wandschneider, Chris
 * Tobin and Andrew Eddie.
 * 
 * Modification by Louis Landry
 * 
 * @copyright: Daniel Morris
 * @email: dan@rootcube.com
 * @license: GNU General Public License (GPL)
 */
class InputFilter {

	/** 
	  * Constructor for inputFilter class. Only first parameter is required.
	  * @access constructor
	  */
	function inputFilter() {

	}

	/** 
	  * Method to be called by another php script. Processes for XSS and specified bad code.
	  * @access public
	  * @param Mixed $source - input string/array-of-string to be 'cleaned'
	  * @return String $source - 'cleaned' version of input parameter
	  */
	public function process($source)
	{
		// Hetfield - 2010-03-01 - removed wrong placed code
		// clean all elements in this array    
		if (is_array($source)) {
			// BOF Hetfield - 2010-03-15 - Bugfix missing brace at foreach
			foreach ($source as $key => $value) {
				// filter element for XSS and other 'bad' code etc.
				$tmp_key = $key;
				unset ($source[$key]);
				$key = $this->remove($this->decode($key));
				if ($key != $tmp_key) {
					return $source;
				} else {
					if (is_string($value)) {
						$source[$key] = $this->remove($this->decode($value));
					} elseif (is_array($value)) {
						$source[$key] = $this->process($value); 
					}
				}
			}
			// EOF Hetfield - 2010-03-15 - Bugfix missing brace at foreach
			return $source;
			// clean this string
		// BOF Hetfield - 2010-03-01 - Bugfix wrong closed if-else
		} else {
			if (is_string($source)) {
				// filter source for XSS and other 'bad' code etc.
				return $this->remove($this->decode($source));
				// return parameter as given
			} else {
				return $source;
			}
		}
		// EOF Hetfield - 2010-03-01 - Bugfix wrong closed if-else
	}

	/** 
	  * Internal method to iteratively remove all unwanted tags and attributes
	  * @access protected
	  * @param String $source - input string to be 'cleaned'
	  * @return String $source - 'cleaned' version of input parameter
	  */
	protected function remove($source) {
		// provides nested-tag protection
		while ($source != $this->filterTags($source)) {
			$source = $this->filterTags($source);
		}
		return $source;
	}

	/** 
	  * Internal method to strip a string of certain tags
	  * @access protected
	  * @param String $source - input string to be 'cleaned'
	  * @return String $source - 'cleaned' version of input parameter
	  */
	protected function filterTags($source) {
    //fix null byte injection
    if (strpos($source,"\0")!== false) {return '';}
    if (strpos($source,"\x00")!== false) {return '';}
    if (strpos($source,"\u0000")!== false) {return '';}
    if (strpos($source,"\000")!== false) {return '';}
    //clean input string
    return strip_tags($source);
	}

	/** 
	  * Try to convert to plaintext
	  * @access protected
	  * @param String $source
	  * @return String $source
	  */
	protected function decode($source = '') {
		if ($source!='') {
			// url decode
			if (function_exists('html_entity_decode')) {
				$source = html_entity_decode($source, ENT_QUOTES, "ISO-8859-1");
			}
			// convert decimal
			$source = preg_replace_callback('/&#(\d+);/m', function ($m) { return chr($m[1]); }, $source); // decimal notation
			// convert hex
			$source = preg_replace_callback('/&#x([a-f0-9]+);/mi', function ($m) { return chr('0x'.$m[1]); }, $source); // hex notation
		}
		return $source;
	}

	/** 
	  * Method to be called by another php script. Processes for SQL injection
	  * @access public
	  * @param Mixed $source - input string/array-of-string to be 'cleaned'
	  * @param string $connection - An open MySQL connection name
	  * @return String $source - 'cleaned' version of input parameter
	  */
	public function safeSQL($source, $connection = 'db_link') {
			// clean all elements in this array
		if (is_array($source)) {
			foreach ($source as $key => $value)
				// filter element for SQL injection
				if (is_string($value))
					$source[$key] = $this->quoteSmart($this->decode($value), $connection);
			return $source;
			// clean this string
		} else {
			if (is_string($source)) {
				// filter source for SQL injection
				if (is_string($source))
					return $this->quoteSmart($this->decode($source), $connection);
				// return parameter as given
			} else
				return $source;
		}
	}

	/** 
	  * @author Chris Tobin
	  * @author Daniel Morris
	  * @access protected
	  * @param String $source
	  * @param string $connection - An open MySQL connection name
	  * @return String $source
	  */
	protected function quoteSmart($source, $connection) {
		// strip slashes
		if (get_magic_quotes_gpc())
			$source = stripslashes($source);
		// quote both numeric and text
		$source = $this->escapeString($source, $connection);
		return $source;
	}

  /**
   * @author Chris Tobin
   * @author Daniel Morris
   * @access protected
   * @param string $string
   * @param string $connection - An open MySQL connection name
   * @return String
   */
	protected function escapeString($string, $connection) {
		return xtc_db_input($string, $connection);
	}
}
?>