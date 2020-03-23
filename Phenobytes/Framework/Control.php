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

		protected function route($Data){
			$this->Route = (is_array($Data) ? $Data : array());
		}

		public function __getroute(){
			return $this->Route;
		}
	}
?>