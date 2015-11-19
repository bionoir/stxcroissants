<?php

namespace STX\CroissantsBundle\Tools;


class factsFromChuckNorris {
	
	private $factsList;
	
	function __construct(){
		$this->factsList = array();
		
		$jsonurl = "http://www.chucknorrisfacts.fr/api/get?data=tri:alea";
		$json = file_get_contents($jsonurl);
		$json_output = json_decode($json);
		
		foreach($json_output as $value){
			array_push($this->factsList, $value->fact);
		}
		
	}
	
	function getFactsList(){
		return $this->factsList;
	}
	
}


?>