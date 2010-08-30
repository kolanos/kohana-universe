# Sprig

A database modeling system for the [Kohana framework](http://kohanaphp.com/) (v3.0+).

## Quick Start

Each model must:

* extend the `Sprig` class
* define a protected `_init()` method and set the field mappings

Example of a model:

    class Model_Post extends Sprig {
        
        protected function _init()
        {
            $this->_fields += array(
                'id' => new Sprig_Field_Auto,
                'title' => new Sprig_Field_Char,
                'blog' => new Sprig_Field_BelongsTo(array(
                    'model' => 'Blog',
                )),
                'author' => new Sprig_Field_BelongsTo(array(
                    'model' => 'User',
                )),
                'body' => new Sprig_Field_Text,
                'published' => new Sprig_Field_Boolean,
            );
        }
        
    }

## Interacting with models

Loading models is done with the `Sprig::factory($name)` method:

    $post = Sprig::factory('post');

Loading models by calling `new Model_Foo` will not work! You *must* use the `factory()` method.

### Data

Model data is read using object properties:

    $title = $post->title;
    $body  = $post->body;

Model data is changed the same way:

    $post->title = 'A New Title';

You can also use the `values()` method set many fields using an associative array:

    $post->values(array(
        'title' => 'A New Title',
    ));

### Create, Read, Update, and Delete (CRUD)

Reading records is done by setting the search values, then calling the `load()` method:

    $post = Sprig::factory('post');
    $post->id = 5;
    $post->load();
    
    if ($post->loaded())
    {
        // Do something with the post
    }

It is also possible to pre-populate the model using an array of values:

    $post = Sprig::factory('post', array('id' => 10))
        ->load();

Creating new records is done using the `create()` method:

    $post = Sprig::factory('post', array(
            'title'     => 'My First Blog Post',
            'body'      => 'Created using a Sprig model!',
            'published' => FALSE,
        ));
    
    // Create a new blog post
    $post->create();

If the model data does not satisfy the validation requirements, a `Validate_Exception` will be thrown. This exception should be caught and used to show the end user the error messages:

    try
    {
        // Create a new blog post
        $post->create();
    }
    catch (Validate_Exception $e)
    {
        // Get the errors using the Validate::errors() method
        $errors = $e->array->errors('blog/post');
    }

Updating a record is done using the `update()` method:

    if ($_POST)
    {
        try
        {
            $post->values($_POST)->update();
        }
        catch (Validate_Exception $e)
        {
            $errors = $e->array->errors('blog/post');
        }
    }

*Note that you must always call `load()` before `update()` or the query will not be built properly.*

Deleting a record is done using the `delete()` method:

    $post->delete();

## Forms

It is possible to generate a complete form very quickly using the `inputs()` method:

    <dl>
    <?php foreach ($post->inputs() as $label => $input): ?>
        <dt><?php echo $label ?></dt>
        <dd><?php echo $input ?></dd>
        
    <?php endforeach ?>
    </dl>

Each input will be populated with the current value of the field.

If you need the field name as the `inputs()` key instead of the label, use `FALSE`:

    $inputs = $post->inputs(FALSE);
    
    echo $inputs['title'];

### Customizing the Form

Creating custom forms can by done using the `input()` method to create place individual fields:

    <div class="post-title">
        <?php echo $post->input('title', array('class' => 'spellcheck fancy')) ?>
    </div>
    <div class="post-body">
        <?php echo $toolbar ?>
        <?php echo $post->input('body', array('class' => 'wysiwyg')) ?>
    </div>

This allows much finer control of how your forms are displayed, at gives control over what fields will be updated.

## Field Object Reference

Accessing a field object is done using the `field()` method:

    $title = $post->field('title');

An array of fields can be accessed using the `fields()` method:

    $fields = $post->fields();

### Types of fields

Sprig offers most database column types as classes. Each field must extend the `Sprig_Field` class. Each field has the following properties:

empty
:  Allow `empty()` values to be used. Default is `FALSE`.

primary
:  A primary key field. Multiple primary keys (composite key) can be specified. Default is `FALSE`.

unique
:  This field must have a unique value within the model table. Default is `FALSE`.

null
:  Convert all `empty()` values to `NULL`. Default is `FALSE`.

editable
:  Show the field in forms. Default is `TRUE`.

default
:  Default value for this field. Default is `''` (an empty string).

choices
:  Limit the value of this field to an array of choices. This will change the form input into a select list. No default value.

column
:  Database column name for this field. Default will be the same as the field name,
   except for foreign keys, which will use the field name with `_id` appended.
   In the case of HasMany fields, this value is the column name that contains the
   foreign key value.

label
:  Human readable label. Default will be the field name converted with `Inflector::humanize()`.

description
:  Description of the field. Default is `''` (an empty string).

filters
:  Validate filters for this field.

rules
:  Validate rules for this field.

callbacks
:  Validate callbacks for this field.

#### Sprig_Field_Auto

An auto-incrementing (sequence) field.

Implies `primary = TRUE` and `editable = FALSE`.

#### Sprig_Field_Boolean

A boolean (TRUE/FALSE) field, representing by a checkbox.

Implies `empty = TRUE` and `default = FALSE`.

#### Sprig_Field_Char

A single line of text, represented by a text input.

Also has the `min_length` and `max_length` properties.

#### Sprig_Field_Password

A password, represented by a password input.

Also has the `hash_with` property, a [callback](http://php.net/callback) used to hash new values.

*Note: For security reasons, the input generated by a password field will never have a value attribute.*

#### Sprig_Field_Float

A float or decimal number, represented by a text input.

Also has the `places` property.

#### Sprig_Field_Integer

An integer number, represented with a text input (or a select input, if the `choices` property is set).

Also has the `min_value` and `max_value` properties.

#### Sprig_Field_Text

A large block of text, represented by a textarea.

#### Sprig_Field_Enum

Extends `Sprig_Field_Char`, but requires the `choices` property.

#### Sprig_Field_Email

Extends `Sprig_Field_Char`, but requires a valid email address as the value.

#### Sprig_Field_Timestamp

Extends `Sprig_Field_Integer`, but requires a valid UNIX timestamp as the value.

Also has the `format` (any string accepted by [date](http://php.net/date)) and `auto_now_create` and `auto_now_update` properties.

#### Sprig_Field_Image

Extends `Sprig_Field_Char`, represents an image file.

Requires the `path` property, the path to the directory where images will be stored.

#### Sprig_Field_HasOne

A reference to another model by the parent model primary key value. Does not produce a form input.

Has the `model` property, the name of another Sprig model.

#### Sprig_Field_BelongsTo

A reference to another model by the child model primary key value. Represented by a select input.

Has the `model` property, the name of another Sprig model.

#### Sprig_Field_HasMany

A reference to many other models by this model primary key value. Does not produce a form input.

Has the `model` property, the name of another Sprig model.

#### Sprig_Field_ManyToMany

A reference to another model by a pivot table that contains the both primary keys. Represented by a list of checkbox inputs.

Has the `model` property, the name of another Sprig model.

Has the `through` property, the name of the pivot table. By default, uses both model names, sorted alphabetically and combined with an underscore. For example: a many-to-many relationship between `Model_Post` and `Model_Tag` would default to `post_tag` as the table name.

