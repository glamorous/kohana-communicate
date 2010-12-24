<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Communicate abstract class where all subclasses has to extend
 *
 * @package    Communicate Provider
 * @author     Jonas De Smet
 * @copyright  (c) 2010 Glamorous.be
 * @license    BSD
 */
abstract class Communicate_Provider
{
	/*
	 * @var string sender
	 */
	protected $_from;

	/*
	 * @var array recipients with for each recipient as array('email' => $address, 'name' => $name)
	 */
	protected $_to = array();

	/*
	 * @var array cc-recipients with for each recipient as array('email' => $address, 'name' => $name)
	 */
	protected $_cc = array();

	/*
	 * @var array bcc-recipients with for each recipient as array('email' => $address, 'name' => $name)
	 */
	protected $_bcc = array();

	/*
	 * @var string subject
	 */
	protected $_subject;

	/*
	 * @var string reply to
	 */
	protected $_replyto;

	/*
	 * @var string HTML message
	 */
	protected $_htmlbody;

	/*
	 * @var string Plain text message
	 */
	protected $_plainbody;

	/*
	 * Add sender to the email
	 */
	public function addFrom($email, $name = NULL)
	{
		$this->_from = array('email' => $email, 'name' => $name);
		return $this;
	}

	/*
	 * Add reply-to to the email
	 */
	public function addReplyTo($email, $name = NULL)
	{
		$this->_replyto = array('email' => $email, 'name' => $name);
		return $this;
	}

	/*
	 * Add someone for the To-field
	 */
	public function addTo($email, $name = NULL)
	{
		return $this->_addRecipient('to', $email, $name);
	}

	/*
	 * Add someone for the Cc-field
	 */
	public function addCc($email, $name = NULL)
	{
		return $this->_addRecipient('cc', $email, $name);
	}

	/*
	 * Add someone for the Bcc-field
	 */
	public function addBcc($email, $name = NULL)
	{
		return $this->_addRecipient('bcc', $email, $name);
	}

	/*
	 * Add subject to the email
	 */
	public function addSubject($subject)
	{
		return $this->_subject = $subject;
	}

	/*
	 * Add message for the email
	 */
	public function addMessage($html_body = NULL, $plain_body = NULL)
	{
		$this->_htmlbody = (is_null($html_body)) ? '' : $html_body;
		$this->_plainbody = (is_null($plain_body)) ? strip_tags($this->_htmlbody) : $plain_body;

		return $this;
	}

	/*
	 * Recipient-handler
	 *
	 * @return $this
	 */
	protected function _addRecipient($type, $email, $name)
	{
		$data = array('email' => $email, 'name' => $name);

		switch($type)
		{
			case 'to':
				$this->_to[] = $data;
				break;
			case 'cc':
				$this->_cc[] = $data;
				break;
			case 'bcc':
				$this->_bcc[] = $data;
				break;
			default:
				throw Kohana_Exception('Not a valid recipient');
				break;
		}

		return $this;
	}

	/*
	 * Setup the class with specific stuff for the subclass
	 *
	 * @return $this
	 */
	public abstract function setup(array $data = array());

	/**
	 * Sends the email.
	 *
	 * @return  bool
	 */
	public abstract function send();
} // End Communicate Provider