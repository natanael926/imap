<?php namespace NCrousset\Imap;

class Config
{

	/**
	 * instancia de la clase
	 * 
	 * @var Config
	 */
	private static $instance = null;

	/**
	 * @var string
	 */
	public $host;

	/**
	 * @var string
	 */
	public $username;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @var int
	 */
	public $postImap;

	/**
	 * @var int
	 */
	public $postSmtp;

	/**
	 * 
	 * @param string $pathConfigIni 
	 * @return  void
	 */
	public function __construct()
	{
		$this->host = env("HOST_IMAP");
		$this->username = env("USER_EMAIL_IMAP");
		$this->password = env("PASS_EMAIL_IMAP");
		$this->postImap = env("POST_IMAP");
		$this->postSmtp = env("POST_SMTP");
	}

	/**
	 *
	 * @return Config 
	 * @return Config
	 */
	public static function getInstance()
	{
		if(self::$instance == null) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	 
}