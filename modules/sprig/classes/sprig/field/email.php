<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig email field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_Email extends Sprig_Field_Char {

	public $rules = array('email' => NULL);

} // End Sprig_Field_Email