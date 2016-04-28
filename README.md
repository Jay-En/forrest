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
$agents = $forrest->select('agent',[
		"[>]user" => [
			"user_id" => "user_id"
		]
    ],[
		'name as agent_name',
		'agent_id'
	],[
		"agent.user_id[>]" => 10,
		"LIMIT"	=> 5,
		"ORDER" => "agent.user_id"
]);

```
## INSERT 
```php
//table($tablename)
$forrest->table('account_access');
//add rules condition
        ->addparameter([
						'internal_user_id' => "required|numeric",
						'user_id'		   => "unique|numeric"
		]);

//validate then insert if valid
$result = $forrest->insert([
								'internal_user_id' => "1",
								'user_id'		   => 1
						]);
						

```
## UPDATE 
```php
$forrest->set('account_access');

$forrest->addparameter([
						'internal_user_id' => "required|numeric",
						'user_id'		   => "required|numeric"
					]);

$result = $forrest->update([
                                'user_id' => 2
							],[
								'user_id' => 1
							]);
```
## License

Forrest is under the MIT license.

## DEPENDENCIES

* Medoo : [http://medoo.in](http://medoo.in)
* Gump : [https://github.com/Wixel/GUMP](https://github.com/Wixel/GUMP)
