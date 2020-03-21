<?php
	namespace Phenobytes\Framework;

	class Control extends Seed{
		private $Route = array();

		public function __construct(){
			parent::__construct();
		}

		public function __destruct(){
			parent::__destruct();
		}

		protected function SetRoute($Data){
			$this->Route = (is_array($Data) ? $Data : array());
		}

		public function GetRoute(){
			return $this->Route;
		}

		public function http(
			\Psr\Http\Message\ResponseInterface $Response,
			$Content = "",
			$Status = 200
		){
			$Response->getBody()->write($Content);
			return $Response->withStatus((is_integer($Status) ? $Status : 200));
		}
	}
?>