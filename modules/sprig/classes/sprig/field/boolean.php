<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig boolean field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_Boolean extends Sprig_Field {

	public $empty = TRUE;

	public $default = FALSE;

	public $filters = array('filter_var' => array(FILTER_VALIDATE_BOOLEAN));

	public $append_label = TRUE;

	public function value($value)
	{
		return (boolean) $value;
	}

	public function verbose($value)
	{
		return $value ? 'Yes' : 'No';
	}

	public function input($name, $value, array $attr = NULL)
	{
		$checkbox = Form::checkbox($name, 1, $this->value($value), $attr);
		if ($this->append_label)
		{
			$checkbox = "<label>{$checkbox} {$this->label}</label>";
		}
		return $checkbox;
	}

} // End Sprig_Field_Boolean