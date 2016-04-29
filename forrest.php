<?php

require_once dirname(__FILE__)."/medoo.php";


class forrest extends medoo
{

	protected $parameter;
	protected $table;
	public $error;
	public $validator;
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->validator = new GUMP();
		$this->custom_validation();

	}

	public function custom_validation()
	{
		
		GUMP::add_validator("unique", function($field, $input, $param = NULL) {
			//var_dump($this->table,$input[$field]);exit;
	        $param = trim(strtolower($param));

	        $value = trim(strtolower($input[$field]));

			if(!$this->has($this->table,[$field => $input[$field]]))
				return true;

			return false;
	    });


		GUMP::add_validator("optional", function($field, $input, $param = NULL) {
	        return true;
	    });

		GUMP::add_validator("notnull", function($field, $input, $param = NULL) {
			if(!isset($input[$field])){
				return true;
			}
			if($input[$field]){
	        	return true;
			}

			return false;
	    });



	}

	public function addparameter($array, $table = "")
	{
		if(!$table){
			$table = $this->table;
		}

		$this->parameter = [$table => $array];
	}

	public function table($table)
	{
		$this->table = $table;
	}

	public function insert($param)
	{
		if(isset($this->parameter[$this->table])){
			return $this->validateParams($param, $this->parameter[$this->table], function($err, $params){
					if($err){
						$this->error = $err;
						return false;
					}

			$result = parent::med_insert($this->table, $params);

			if($this->error()[2]){
				$this->error = $this->error()[2];
				return false;
			}

			return $result;



			});
		}
		else{

			$result = parent::med_insert($this->table, $params);

			if($this->error()[2]){
				$this->error = $this->error()[2];
				return false;
			}

			return $result;

		}
	}

	public function update($param, $where)
	{
		if(isset($this->parameter[$this->table])){
			return $this->validateParams($param, $this->removerequire($this->parameter[$this->table]), function($err, $params) use ($where){
					if($err){
						$this->error = $err;
						return false;
					}

			return parent::med_update($this->table, $params, $where);

			});
		}
		else{
			return parent::med_update($this->table, $params, $where);
		}
	}


	public function validateParams($request, $validParam, $callback)
	{
		

        if(is_array($request)){
			
			$param = $request;
			
		}else{
        	$param  = $request->getParsedBody();
		}

		if(!$param){
			return $callback(["Input Error"], null);
		}
		
		foreach ($validParam as $key => $value) {
			if(is_array($value)){

				if(is_array($value)){
					if(empty($value[0]))
					{
						$rule[$key] = 'validParam';
					} else {
						$rule[$key] = $value[0];
					}

					if (isset($value[1])){
						$filter[$key] = $value[1];
					}

				} else {
					$rule[$key] = $value;
				} 	
			} else {
				if (!empty($value)){
					$rule[$key] = $value;
				} else {
					$rule[$key] = 'validParam';
				}
			}
		}
		$param = $this->validator->sanitize( $param, array_keys($rule));
		if(isset($filter)){
			$param = $this->validator->filter($param, $filter);
		}
		$validated = $this->validator->validate( $param, $rule);

		if($validated === true){
            return $callback(null, $param);
            
		} else { 
            
			$response['error']   = true;
        
            $response = $this->validator->get_errors_array();
			
            return $callback($response, null);
		}
	}


	#remove required fields

	function removerequire($params)
	{
		foreach ($params as $key => $value) {
			$params[$key] = str_replace("required", "notnull", $value);
		}

		return $params;
	}

}