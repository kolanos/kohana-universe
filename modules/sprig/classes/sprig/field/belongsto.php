<?php defined('SYSPATH') or die('No direct script access.');

class Sprig_Field_BelongsTo extends Sprig_Field_ForeignKey {

	public $in_db = TRUE;

	public function input($name, $value, array $attr = NULL)
	{
		$model = Sprig::factory($this->model);

		$choices = $model->select_list($model->pk());

		if ($this->empty)
		{
			Arr::unshift($choices, '', '-- '.__('None'));
		}

		return Form::select($name, $choices, $this->verbose($value), $attr);
	}

} // End Sprig_Field_BelongsTo
