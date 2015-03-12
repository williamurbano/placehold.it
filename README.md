# placehold.it
> View Helper to generate placehold.it URLs

This module is designed to work with the View Helpers from Zend Framework 2.

### Installation
```bash
php composer.phar require williamurbano/placehold.it:dev-master
```

### Configuration
```php
<?php // config/application.config.php

return array(
	'modules' => array(
		'PlaceholdIt',
		'Application',
		// ... Your another modules
	)
);

?>
```

### Options
The helper has some options that follow are listed below

| Option | Required | Type               | Default | Description |
| ------ | -------- | ------------------ | ------- | ----------- |
| size   |   true   | array, int, string |         | Defines the dimensions of the image. If declared an integer or a string width and height take the same value. If an array is declared the first position will match the width and the second time. See the following [examples](#usage). |
| text   |  false   | string             | NULL    | Set the text to the image content. |
| colors |  false   | array, int, string | NULL    | Sets the color scheme (hexadecimal) of the image. Background and text color. If declared an integer or a string the image background will take informed color. If an array is defined its first position will match the background color and the second image text color. |
| format |  false   | string             | NULL    |
