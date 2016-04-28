<?php
require "../forrest.php";



$forrest = new forrest([
							"database_type" => "mysql",
							"database_name"	=> "b2bhotels", 
							"server"	    => "localhost",
							"username"		=> "root",
							"password"		=> "leandevinc321",
							"charset"	 	=> "utf8",

						]);


$forrest->table('account_access');

$forrest->addparameter([
						'internal_user_id' => "required|numeric",
						'user_id'		   => "required|numeric"
					]);

$result = $forrest->update([
								'user_id' => 2
							],[
								'user_id' => 1
							]);

echo json_encode($result);exit;
