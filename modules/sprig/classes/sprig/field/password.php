<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig password field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_Password extends Sprig_Field_Char {

	public $hash_with = 'sha1';

	public function input($name, $value, array $attr = NULL)
	{
		// Never add a value to a password field for security reasons
		return Form::password($name, NULL, $attr);
	}

} // End Sprig_Field_Password
