<?php
require "../forrest.php";



$account_access = new forrest(
						[
							"database_type" => "mysql",
							"database_name"	=> "database", 
							"server"	    => "localhost",
							"username"		=> "root",
							"password"		=> "password",
							"charset"	 	=> "utf8",

						]	
					);


$account_access->table('account_access');

$account_access->addparameter([
								'internal_user_id' => "required|numeric",
								'user_id'		   => "required|numeric"
							]);

$result = $account_access->insert([
								'internal_user_id' => "1",
								'user_id'		   => 1
						]);


echo json_encode($result);exit;
