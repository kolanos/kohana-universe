<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig "has one" relationship field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_HasOne extends Sprig_Field_ForeignKey {

	public $editable = FALSE;

	public function input($name, $value, array $attr = NULL)
	{
		$model = Sprig::factory($this->model);

		$choices = $model->select_list($model->pk());

		return Form::select($name, $choices, $this->verbose($value));
	}

} // End Sprig_Field_HasOne
