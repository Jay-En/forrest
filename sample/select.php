<?php
require "../forrest.php";



$user = new forrest(
						[
							"database_type" => "mysql",
							"database_name"	=> "B2Bhotels", 
							"server"	    => "localhost",
							"username"		=> "root",
							"password"		=> "leandevinc321",
							"charset"	 	=> "utf8",

						]	
					);

$agents = $user->debug()->select('agent',
					["[>]user" => "ON user.user_id = agent.user_id"]

	,['name','agent_id'],
	[
	"agent.user_id[>]" => 10,
	"LIMIT"	=> 5,
	"ORDER" => "agent.user_id"
	]);

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
$agents = $user->select('agent',[
									"[>]user" => [
													"user_id" => "user_id"
												]
							],[
									'name as agent_name',
									'agent_id'
									],
									[
									"agent.user_id[>]" => 10,
									"LIMIT"	=> 5,
									"ORDER" => "agent.user_id"
]);


echo json_encode($agents);exit;
