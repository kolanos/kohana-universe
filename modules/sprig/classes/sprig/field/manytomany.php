<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig "has and belongs to many" relationship field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_ManyToMany extends Sprig_Field_HasMany {

	public $editable = TRUE;

	public $through;

} // End Sprig_Field_ManyToMany
