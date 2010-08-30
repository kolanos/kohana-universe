<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig floating point number field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_Float extends Sprig_Field {

	public $places;

	public function value($value)
	{
		if ($this->null AND empty($value))
		{
			// Empty values are converted to NULLs
			$value = NULL;
		}
		else
		{
			if (is_string($value))
			{
				$locale = localeconv();

				// Locale-aware conversion from string to float:
				// - Remove the thousands separator
				// - Replace the decimal point with a period
				$value = str_replace(array($locale['thousands_sep'], $locale['decimal_point']), array('', '.'), $value);
			}

			$value = floatval($value);
		}

		return $value;
	}

	public function verbose($value)
	{
		if (is_numeric($value))
		{
			if ($this->places)
			{
				return number_format($value, $this->places);
			}
			else
			{
				return (string) $value;
			}
		}
		else
		{
			return '';
		}
	}

} // End Sprig_Field_Float
