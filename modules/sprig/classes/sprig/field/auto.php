<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig auto-incrementing field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_Auto extends Sprig_Field_Integer {

	public $primary = TRUE;

	public $editable = FALSE;

	public $null = TRUE;

} // End Sprig_Field_Auto
