<?php
/**
 * Abstract attachment class
 *
 * @package    phpMyFAQ
 * @license    MPL
 * @author     Anatoliy Belsky <ab@php.net>
 * @since      2009-08-21
 * @version    SVN: $Id: Abstract.php 4459 2009-06-10 15:57:47Z thorsten $
 * @copyright  2009 phpMyFAQ Team
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 */

/**
 * PMF_Atachment_Interface
 * 
 * @package    phpMyFAQ
 * @license    MPL
 * @author     Anatoliy Belsky <ab@php.net>
 * @since      2009-08-21
 * @version    SVN: $Id: Abstract.php 4459 2009-06-10 15:57:47Z thorsten $
 * @copyright  2009 phpMyFAQ Team
 */
interface PMF_Attachment_Interface
{
	/**
	 * Attachment id
	 * 
	 * @var int
	 */
	protected $id;
	
	/**
	 * The key to encrypt with
	 * 
	 * @var string
	 */
	protected $key;
	
	/**
	 * Errors
	 * @var array
	 */
	protected $error = array();
	
	/**
	 * Database instance
	 * 
	 * @var unknown_type
	 */
	protected $db;
	
	protected $recordId;
	protected $recordLang;
	protected $hash;
	protected $filename;
	protected $encrypted;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $db;
		
		$this->db = &$db;
		
		if($this->id) {
			$this->getMeta();
		}
	}
	
	/**
	 * Build attachment url
	 * 
	 * @param boolean $forHTML either to use ampersands directly
	 * 
	 * @return string
	 */
	public function buildUrl($forHTML = true)
	{
		
	}
	
	/**
	 * Set encryption key
	 * 
	 * @param string $key
	 * 
	 * @return null
	 */
	public function setKey($key)
	{
		$this->key = $key;	
	}
	
	/**
	 * Get meta data
	 * 
	 * @return boolean
	 */
	protected function getMeta()
	{
		$retval = false;
		
		$sql = sprintf('SELECT record_id, record_lang,
		                       hash, filename, encrypted
		                WHERE id = %d', (int)$this->id);
		
		$result = $this->db->query($sql);
		
		if($result) {
			$assoc = $this->db->fetch_assoc($result);
			
			if(!empty($assoc)) {
			    $this->recordId = $assoc['record_id'];
			    $this->recordLang = $assoc['record_lang'];
			    $this->hash = $assoc['hash'];
			    $this->filename = $assoc['filename'];
			    $this->encrypted = $assoc['encrypted'];
			    
			    $retval = true;
			}
		}
		
		return $retval;
	}
}