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

		public function content($Response, $Body, $Status = 200, $Header = array()){
			$Response->getBody()->write($Body);

			if(is_array($Header)){
				foreach($Header as $key => $value){
					if(is_string($key) && is_string($value)){
						$Response = $Response->withHeader($key, $value);
					}
				}
			}

			return $Response->withStatus((is_int($Status) ? $Status : 200));
		}

		public function template($Response, $ViewName, $ViewData = array(), $Status = 200, $Header = array()){
			return $this->content($Response, $this->view($ViewName, $ViewData), $Status, $Header);
		}

		public function redirect($Response, $URL, $Permanent = false){
			return $this->content($Response,
				"<!DOCTYPE html>" .
				"<html>" .
					"<head>" .
						"<meta http-equiv=\"refresh\" content=\"0; url=" . $URL . "\" />" .
						"<meta name=\"author\" content=\"Samy, samy@phenobytes.com\" />" .
						"<meta name=\"description\" content=\"Redirect\" />" .
						"<meta name=\"generator\" content=\"Phenobytes PHP Framework\" />" .
					"</head>" .
					"<body>Redirect to <a href=\"" . $URL . "\">" . $URL . "</a></body>" .
				"</html>",
				($Permanent ? 301 : 302),
				array("Location" => $URL)
			);
		}

		protected function route($Data){
			$this->Route = (is_array($Data) ? $Data : array());
		}

		public function __getroute(){
			return $this->Route;
		}
	}
?>