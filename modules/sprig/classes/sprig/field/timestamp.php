<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig timestamp field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_Timestamp extends Sprig_Field_Integer {

	public $auto_now_create = FALSE;

	public $auto_now_update = FALSE;

	public $default = NULL;

	public $format = 'Y-m-d G:i:s A';

	public function value($value)
	{
		if ($value AND is_string($value) AND ! ctype_digit($value))
		{
			$value = strtotime($value);
		}

		return parent::value($value);
	}

	public function verbose($value)
	{
		if (is_integer($value))
		{
			return date($this->format, $value);
		}
		else
		{
			return '';
		}
	}

} // End Sprig_Field_Timestamp
