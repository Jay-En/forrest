<?php
require "../forrest.php";



$test = new forrest(
						[
							"database_type" => "mysql",
							"database_name"	=> "database", 
							"server"	    => "localhost",
							"username"		=> "root",
							"password"		=> "password",
							"charset"	 	=> "utf8",

						]	
					);


$result = $test ->table('test')
				->rules([
							'number' 			=> "required|numeric",
							'text'		   		=> ["required|min_len,6", "trim"],
							'email'		   	=> "required|valid_email"
						])
				->insert([
								'number' 	   => "1",
								'text'		   => "                 Forrest Test Text                 ",
								'email'		   => "me@jnbruno.com"
						]);

if($test->error()){
	echo json_encode($test->error());
}

echo json_encode($result);


