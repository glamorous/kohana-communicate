<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Wrapper class.for sending mails
 *
 * @package    Communicate
 * @author     Jonas De Smet
 * @copyright  (c) 2010 Glamorous.be
 * @license    BSD
 */
abstract class Communicate_Core
{
	/*
	 * @var $_instance of the subclass
	 */
	protected static $_instance;

	/**
	 * Creates a new Communicate object.
	 *
	 * @param   array  configuration
	 * @return  Pagination
	 */
	public static function factory($type = NULL)
	{
		try
		{
			$class = 'Communicate_'.ucfirst($type);
			Communicate::$_instance = new $class();
			return Communicate::$_instance;
		}
		catch(Exception $ex)
		{
			throw Kohana_Exception('Class Communicate_'.ucfirst($type).' can not be loaded');
		}
	}
} // End Communicate