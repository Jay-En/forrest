<?php

require_once dirname(__FILE__)."/medoo.php";


class forrest extends medoo
{

	protected $parameter;
	protected $table;
	protected $sanitize = false;
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


		GUMP::add_validator("array", function($field, $input, $param = NULL) {
			
			if(isset($input[$field])){
				return is_array($input[$field]);
			}
	    });


	}

	public function rules($array, $table = "")
	{
		if(!$table){
			$table = $this->table;
		}

		$this->parameter = [$table => $array];

		return $this;
	}

	public function table($table)
	{
		$this->table = $table;

		return $this;
	}

	public function insert($table, $param = null)
	{
		if(is_string($table)){
			$this->table = $table;
		}else{
			$param = $table;
		}

		if(!is_array($param)){
			throw new Exception('Insert parameter should be an array');
		}

		return $this->insert_db($param);
	}

	private function insert_db($param)
	{
		if(!$this->table){
			throw new Exception('Table is not set');
		}
		if(isset($this->parameter[$this->table])){
			return $this->validateParams($param, $this->parameter[$this->table], function($err, $params){
					if($err){
						$this->error = $err;
						return false;
					}

			$result = parent::med_insert($this->table, $params);

			return $result;



			});
		}
		else{

			$result = parent::med_insert($this->table, $param);

			return $result;

		}
	}

	public function update($table, $param, $where = null)
	{

		if(is_string($table)){
			$this->table = $table;
		}else{
			$where = $param;
			$param = $table;
		}
		
		if(!is_array($param)){
			throw new Exception('Parameter should be an array');
		}
		if(!is_array($where)){
			throw new Exception('Where parameter should be an array');
		}

		return $this->update_db($param,$where);
	}

	private function update_db($params, $where)
	{

		if(!$this->table){
			throw new Exception('Table is not set');
		}
		if(isset($this->parameter[$this->table])){
			return $this->validateParams($params, $this->removerequire($this->parameter[$this->table]), function($err, $params) use ($where){
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


	public function validateParams(array $param,array $validParam, $callback)
	{

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

		foreach ($param as $key => $value) {
			if(is_array($value)){
				$array_params[$key] = $value;
				unset($param[$key]);
			}
		}
		if($this->sanitize === true){
			$param = $this->validator->sanitize( $param, array_keys($rule));
		}

		if(isset($filter)){
			$param = $this->validator->filter($param, $filter);
		}

		if(isset($array_params)){
			$param += $array_params;
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
	public function setSanitize($status = true)
	{
		$this->sanitize = $status;
	}


	function removerequire($params)
	{
		foreach ($params as $key => $value) {
			$params[$key] = str_replace("required", "notnull", $value);
		}

		return $params;
	}


	public function error()
	{
		$error = false;
		if($this->pdo->errorInfo()[2]){
			$error['database error'] = $this->pdo->errorInfo()[2];
		}

		if($this->error){
			$error['validation error'] = $this->error;
		}

		return $error;
	}

}