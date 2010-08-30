<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Basic user model. To use it, create a model that extends this class:
 *
 *     class Model_User extends Sprig_Model_User {}
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
abstract class Sprig_Model_User extends Sprig {

	protected $_title_key = 'username';

	protected $_sorting = array('username' => 'asc');

	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			'username' => new Sprig_Field_Char(array(
				'empty'  => FALSE,
				'unique' => TRUE,
				'rules'  => array(
					'regex' => array('/^[\pL_.-]+$/ui')
				),
			)),
			'password' => new Sprig_Field_Password(array(
				'empty' => FALSE,
			)),
			'password_confirm' => new Sprig_Field_Password(array(
				'empty' => TRUE,
				'in_db' => FALSE,
				'rules' => array(
					'matches' => array('password'),
				),
			)),
			'last_login' => new Sprig_Field_Timestamp(array(
				'empty' => TRUE,
				'editable' => FALSE,
			)),
		);
	}

	/**
	 * Login with a Validate callback:
	 *
	 *     // Create an empty user
	 *     $user = Sprig::factory('user');
	 *
	 *     // Create a Validate instance
	 *     $post = Validate::factory($_POST)
	 *         ->rules('username', $user->field('username')->rules)
	 *         ->rules('password', $user->field('password')->rules)
	 *         ->callback('usernane', array($user, '_login'));
	 *
	 *     // Check the POST and login
	 *     if ($post->check())
	 *     {
	 *         URL::redirect($uri);
	 *     }
	 *
	 * @param   object  Validate instance
	 * @param   string  field name
	 * @return  void
	 */
	public function _login(Validate $array, $field)
	{
		if ($array['username'] AND $array['password'])
		{
			$this->username = $array['username'];
			$this->password = $array['password'];

			// Attempt to load the user by username and password
			$this->load();

			if ($this->loaded())
			{
				// Update the last login time
				$this->last_login = time();
				$this->update();

				Cookie::set('authorized', $this->username);
			}
			else
			{
				$array->error('username', 'invalid');
			}
		}
	}

} // End User
