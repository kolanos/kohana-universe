<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig image field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @author     Kelvin Luck
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_Image extends Sprig_Field_Char {

	/**
	 * @var  integer  image width
	 */
	public $width;

	/**
	 * @var  integer  image height
	 */
	public $height;

	/**
	 * @var  string  directory where the image will be loaded from
	 */
	public $directory;

	/**
	 * @var  integer  one of the Image resize constants
	 */
	public $resize = Image::AUTO;

	/**
	 * @var  array   types of images to accept
	 */
	public $types = array('jpg', 'jpeg', 'png', 'gif');

	public function __construct(array $options = NULL)
	{
		if (empty($options['directory']) OR ! is_dir($options['directory']))
		{
			throw new Sprig_Exception('Image fields must define a directory path');
		}

		// Normalize the directory path
		$options['directory'] = rtrim(str_replace(array('\\', '/'), '/', $options['directory']), '/').'/';

		parent::__construct($options);

		// Handle uploads
		$this->callbacks[] = array($this, '_upload_image');
	}

	public function input($name, $value, array $attr = NULL)
	{
		return Form::file($name, $attr);
	}

	public function verbose($value)
	{
		return $this->directory.$value;
	}

	public function _upload_image(Validate $array, $input)
	{
		if ($array->errors())
		{
			// Don't bother uploading
			return;
		}

		// Get the image from the array
		$image = $array[$input];

		if ( ! Upload::valid($image) OR ! Upload::not_empty($image))
		{
			// No need to do anything right now
			return;
		}

		if (Upload::valid($image) AND  Upload::type($image, $this->types))
		{
			$filename = strtolower(Text::random('alnum', 20)).'.jpg';

			if ($file = Upload::save($image, NULL, $this->directory))
			{
				Image::factory($file)
					->resize($this->width, $this->height, $this->resize)
					->save($this->directory.$filename);

				// Update the image filename
				$array[$input] = $filename;

				// Delete the temporary file
				unlink($file);
			}
			else
			{
				$array->error('image', 'failed');
			}
		}
		else
		{
			$array->error('image', 'valid');
		}
	}

} // End Sprig_Field_Image
