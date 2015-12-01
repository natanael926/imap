<?php namespace NCrousset\Imap;

use NCrousset\Imap\Config as Config;
use NCrousset\Imap\ImapConnect as ImapConnect;

/**
 *
 * @package MailImap
 * @author  Rudys Natanael Acosta <natanael926@gmail.com>
 */
class Imap extends ImapConnect
{

	/**
	 * 
	 * @var Imap
	 */
	private static $instance = null;
	
	/**
	 * 
	 * @var Config
	 */
	private $config = null;

	/**
	 * Ruta del servidor y buzon para el servidor 
	 * la ruta debe ir en {} 
	 * 
	 * @var string
	 */
	protected $authhost;


	/**
	 * 
	 * @param Config $config
	 */
	public function __construct()
	{
		$this->config = Config::getInstance(); //Configuracion predeterminada

		$this->authhost = "{" . $this->config->host .":". $this->config->postImap ."/imap/ssl}";
		parent::__construct($this->authhost, $this->config->username, $this->config->password); 
	}

	/**
	 * 
	 * @return Imap 	
	 */
	public static function getInstance()
	{
		if(self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * 
	 * @param string $authHost 
	 * @return void
	 */
	public function setAuthHost($authHost)
	{
		$this->authhost = $authhost;
	}

	/**
	 * 
	 * @param Config $config
	 * @return void
	 */
	public function setConfig(Config $config)
	{
		$this->config = $config;
	}

    /**
     * Lista de buzones
     * 
     * @return Array 
     */
	public function listMailBoxes()
	{
		$mailBoxes = imap_listmailbox($this->connect, $this->authhost, "*");

        $boxerList = [];

		if($mailBoxes == false) {
			echo "Error! en la llamada";
		} else {
			foreach ($mailBoxes as $boxe) {
				$boxe = str_replace($this->authhost, '',  $boxe);
				array_push($boxerList, $boxe);
			}
		}

		return $boxerList;
	}

	/**
	 * Cabesera de mensage
	 * 
	 * @param  int $numMsg 
	 * @return array
	 */
	public function headerMsg($numMsg, $uid = false)
	{
		//$numMsg es el uid y no el numero de secuencia 
		if($uid) $numMsg =  imap_msgno($this->connect, $numMsg);

		$header = explode("\n", imap_fetchheader($this->connect, $numMsg));

		if (is_array($header) && count($header)) {
        	$head = array();

        	foreach($header as $line) {
         		// separate name and value
         		$regex = "^([^:]*): (.*)";
            	preg_match("%{$regex}%i", $line, $arg);
            	$head[$arg[1]] = $arg[2];
        	}
		}

		return $head;
	}

	/**
	 * 
	 * @param  int $numMsg 
	 * @return text/html
	 */
	public function bodyMsg($numMsg, $uid = false)
	{
		//$numMsg es el uid y no el numero de secuencia 
		if($uid) $numMsg =  imap_msgno($this->connect, $numMsg);

		$body = imap_fetchbody($this->connect, $numMsg, "1");
		return $body;
	}

	/**
	 * [fetchOverview description]
	 * 
	 * @param  int $numMsgStart 
	 * @param  int $numMsgEnd   
	 * @return array             
	 */
	public function fetchOverview($numMsgStart, $numMsgEnd)
	{

		$resurt = imap_fetch_overview($this->connect,"$numMsgStart:{$numMsgEnd}", 0);

		$mailes = array();

		foreach ($resurt as $overview) {
			$mail = [
				'no'      => $overview->msgno,
				'uid'     => $overview->uid,
				'subject' => $overview->subject,
				'from'	  => $overview->from,
				'to'	  => $overview->to,
				'date'	  => $overview->date,
				'recent'  => $overview->recent,
				'flagged' => $overview->flagged,
				'answered'=> $overview->answered,
				'deleted' => $overview->deleted,
				'seen'	  => $overview->seen,
				'draft'	  => $overview->draft
			];


			array_push($mailes, $mail);
		}

		return $mailes;
	}

	/**
	 * 
	 * @return int
	 */
	public function numMsg()
	{
		return imap_num_msg($this->connect);
	}

	/**
	 * Obten id unico de el mensage
	 * 
	 * @param  int $numMsg
	 * @return int 
	 */
	public function getUID($numMsg)
	{
		return imap_uid($this->connect, $numMsg);
	}

	/**
	 * [getUIDBySearch description]
	 * 
	 * @param  string $pattern ej:'FROM "updates.freelancer.com"'
	 * @return array         
	 */
	public function getUIDBySearch($pattern = 'ALL') 
	{
		return imap_search($this->connect, $pattern, SE_UID);
	}
	

	/**
	 * Obtener tipo de contenido
	 * 
	 * @param 
	 * @return string
	 */
	public function getMimeType($structure) 
	{ 

		$primaryMimeType = ["TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"];

    	if(is_object($structure)) { 
        	return $primaryMimeType[(int) $structure->type] . '/' . $structure->subtype; 
     	} 
     	
     	return "TEXT/PLAIN"; 
    } 


    /**
     *
     * Obtener contenido del mail
     *   
     * @param  int $msgNumber  
     * @param  string  $mimeType   
     * @param  boolean $structure  
     * @param  boolean $partNumber 
     * @return boolean             
     */
    public function getPart($msgNumber, $mimeType, $structure = false, $partNumber = false) 
    {

    	if (!$structure) { 
        	$structure = imap_fetchstructure($this->connect, $msgNumber); 
     	}

    	if($structure) { 

        	if($mimeType == $this->getMimeType($structure)) { 

            	if($partNumber == false) { 
                	$partNumber = "1"; 
               	} 
				
				$text = imap_fetchbody($this->connect, $msgNumber, $partNumber); 
              
              	if($structure->encoding == 3) { 
                   return imap_base64($text); 
               	} else if ($structure->encoding == 4) { 
                   return imap_qprint($text); 
               	} else { 
                   return $text; 
            	} 
        	}

         	if ($structure->type == 1) { /* multipart */ 
            	while (list($index, $subStructure) = each($structure->parts)) { 

            		$prefix = 0;

                	if ($partNumber) { 
                    	$prefix = $partNumber . '.'; 
                	} 

                	$data = $this->getPart($msgNumber, $mimeType, $subStructure, $prefix . ($index + 1)); 
                
                	if ($data) { 
                    	return $data; 
                	} 
            	} 
        	} 
    	} 
    	return false; 
	} 
}