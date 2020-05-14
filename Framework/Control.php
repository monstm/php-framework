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

		public function redirect($Response, $URL, $Permanent = false){
			$Response->getBody()->write(
				"<!DOCTYPE html>" .
				"<html>" .
					"<head>" .
						"<meta http-equiv=\"refresh\" content=\"0; url=" . $URL . "\" />" .
						"<meta name=\"author\" content=\"Samy, samy@phenobytes.com\" />" .
						"<meta name=\"description\" content=\"Redirect\" />" .
						"<meta name=\"generator\" content=\"Phenobytes PHP Framework\" />" .
					"</head>" .
					"<body>Redirect to <a href=\"" . $URL . "\">" . $URL . "</a></body>" .
				"</html>"
			);

			return $Response
				->withStatus(($Permanent ? 301 : 302))
				->withHeader("Location", $URL);
		}

		protected function route($Data){
			$this->Route = (is_array($Data) ? $Data : array());
		}

		public function __getroute(){
			return $this->Route;
		}
	}
?>