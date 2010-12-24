<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Communicate subclass to use with Postmark - http://postmarkapp.com/
 * More information about the API error codes: http://developer.postmarkapp.com/developer-build.html#api-error-codes
 *
 * @package    Communicate Postmark subclass
 * @author     Jonas De Smet
 * @copyright  (c) 2010 Glamorous.be
 * @license    BSD
 */
class Communicate_Postmark extends Communicate_Provider
{
	/*
	 * @var const api-url
	 */
	const API_URL = 'http://api.postmarkapp.com/email';

	/*
	 * @var string api-key from Postmark
	 */
	protected $_api_key;

	/*
	 * @var string Postmark tag
	 */
	protected $_tag;

	/**
	 * Setup a new Communicate Postmark object.
	 *
	 * @return  $this
	 */
	public function setup(array $data = array())
	{
		$this->_api_key = Kohana::config('postmark.api_key');
		return $this;
	}

	/**
	 * Set a tag to the email to have easier analytics in Postmark
	 *
	 * @return  $this
	 */
	public function tag($tag)
	{
		$this->_tag = $tag;
		return $this;
	}

	/*
	 * Create an email-address suitable for Postmark
	 *
	 * @param array array like array('address' => $address, 'name' => $name);
	 * @return string
	 */
	protected function _createAddress(array $address)
	{
		return (is_null($address['name'])) ? $address['email'] : $address['name'].' <'.$address['email'].'>';
	}

	/*
	 * Create more then one email-address suitable for Postmark
	 *
	 * @param array array with arrays with addresses;
	 * @return string
	 */
	protected function _createAddresses(array $addresses)
	{
		$data = array();
		foreach($addresses as $address)
		{
			$data[] = $this->_createAddress($address);
		}

		return implode(', ', $data);
	}

	/*
	 * Prepares the data-array to convert to json
	 *
	 * @return array
	 */
	protected function _prepareData()
	{
		// Setting up Postmark email data
		$data = array
		(
			'From' => $this->_createAddress($this->_from),
			'To' => $this->_createAddresses($this->_to),
			'Cc' => $this->_createAddresses($this->_cc),
			'Bcc' => $this->_createAddresses($this->_bcc),
			'Subject' => $this->_subject,
			'Tag' => $this->_tag,
			'HtmlBody' => $this->_htmlbody,
			'TextBody' => $this->_plainbody,
			'ReplyTo' => $this->_createAddress($this->_replyto),
		);

		return $data;
	}

	/**
	 * Sends the email
	 *
	 * @param array  configuration
	 * @return bool
	 */
	public function send()
	{
		$data = $this->_prepareData();

		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'X-Postmark-Server-Token: '.$this->_api_key,
		);

		// Setting CURL options
		$options = array(
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => $headers,
		);

		try
		{
			$result = Remote::get(self::API_URL, $options);
			return TRUE;
		}
		catch(Kohana_Exception $ex)
		{
			if(Kohana::$environment != Kohana::PRODUCTION)
			{
				throw $ex;
			}

			return FALSE;
		}
	}

	/*
	 * Outputs the JSON-data to send to Postmark
	 */
	public function debug()
	{
		return json_encode($this->_prepareData());
	}
} // End Communicate Postmark