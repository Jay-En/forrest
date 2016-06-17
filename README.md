# forrest
>validator and database middleware for PHP

## Get Started

### Install via composer

Add forrest to composer.json configuration file.
```
$ composer require jnbruno/forrest
```

And update the composer
```
$ composer update
```

```php
// Initialize with composer autoload
require 'vendor/autoload.php';


// Initialize
$forrest = new forrest([
    'database_type' => 'mysql',
    'database_name' => 'name',
    'server' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8'
]);
``` 
## SELECT 

```php
//select($table,$join,$where)
$test->select('test',[
			'number as this_is_number',
			'text',
			'email'
			],
			[
			"LIMIT"	=> 5,
			"ORDER" => "number ASC"
]);


```
## INSERT 
```php
//table($tablename)
$result = $test ->table('test')
//add rules condition
				->rules([
							'number' 	 => "required|numeric",
							'text'		 => "required|min_len,6",
							'email'		 => "required|valid_email"
						])

//validate then insert if valid
				->insert([
							'number' 	 => "1",
							'text'		 => "Forrest Test Text",
							'email'		 => "me@jnbruno.com"
						]);

```
## UPDATE 
```php
$result = $test ->table('test')
				->rules([
							'number' 			=> "required|numeric",
							'text'		   		=> ["required|min_len,6", "trim"],
							'email'		   		=> "required|valid_email"
						])

			//	->update($items, $where)
				->update([
								'number' 	   => "1",
								'text'		   => "                 Forrest Test Update                 ",
								'email'		   => "me@jnbruno.com"
						], ['test_id' => 1]);
```
## License

Forrest is under the MIT license.

## DEPENDENCIES

* Medoo : [http://medoo.in](http://medoo.in)
* Gump : [https://github.com/Wixel/GUMP](https://github.com/Wixel/GUMP)
