<?php
require "../forrest.php";



$user_information = new forrest(
						[
							"database_type" => "mysql",
							"database_name"	=> "b2bhotels", 
							"server"	    => "localhost",
							"username"		=> "root",
							"password"		=> "leandevinc321",
							"charset"	 	=> "utf8",

						]	
					);

$user_information->table('user_information');

$user_information->addparameter([
								'first_name' 	=> ["required|min_len,1", "trim"],
								'last_name'		=> ["required|min_len,1", "trim"],
								'user_id'		=> "unique|min_len,1",
							]);

$result = $user_information->insert([
										'first_name'	=> "           JN            ",
										'last_name'	=> "           Bruno            ",
										'user_id'	=> 524
								]);

var_dump($user_information->error);exit;
echo json_encode($result);exit;
