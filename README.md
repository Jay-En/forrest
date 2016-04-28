# forrest
validator and database middleware for PHP

#INSTALL
1. download and extract files
2. require forest.php

#INITIALIZE
$forrest = new forrest(
						[
							"database_type" => "mysql",
							"database_name"	=> "dbname", 
							"server"	    => "localhost",
							"username"		=> "root",
							"password"		=> "password",
							"charset"	 	=> "utf8",

						]	
					);
					
					
#SELECT
$agents = $forrest->select('agent',[
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



#INSERT

$forrest->table('account_access');

$forrest->addparameter([
								'internal_user_id' => "required|numeric",
								'user_id'		   => "required|numeric"
							]);

$result = $forrest->insert([
								'internal_user_id' => "1",
								'user_id'		   => 1
						]);


#UPDATE 

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


