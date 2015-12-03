<?php namespace NCrousset\Imap;

use NCrousset\Imap\Imap as Imap;

/**
 * 
 * @package NCrousset\Imap
 * @author  Rudys Natanael Acosta <natanael926@gmail.com>
 */
trait SetMail {

	/**
	 * Set banderas 
	 * 
	 * @param string $sequenceNo "2,1,3"
	 * @param string $flag \Seen, \Answered, \Flagged, \Deleted, y \Draft 
	 * @return bool
	 */
	public function setFlagged($sequenceNo, $flag)
	{
		return imap_setflag_full($this->connect, $sequenceNo, $flag);
	}

}