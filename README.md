# Flight Sanitizer



### How to install

composer require lawrence72/sanitizer

### How to use

$sanitizer = new Sanitizer();

$text = "Some Text"

$new_text = $sanitizer->clean($text);

### How to use with HTML tags

Include the tags you wish to allow

$sanitizer = new Sanitizer();

$text = "<div>Some Text</div>"

$new_text = $sanitizer->clean($text,['div']);