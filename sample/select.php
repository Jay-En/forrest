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

$result = $test->select('test',[
									'number as this_is_number',
									'text',
									'email'
									],
									[
									"LIMIT"	=> 5,
									"ORDER" => "number ASC"
]);


echo json_encode($result);
