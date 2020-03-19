<?php
	namespace framework;

	class base{
		public function __construct(){}

		public function __destruct(){}

		protected function config(){}
		protected function control(){}
		protected function model(){}
		protected function view(){}

		protected function trace($Message){
			try{
				$backtrace = array_slice(debug_backtrace(), 1);
				error_log($Message . (count($backtrace) > 0 ? " " . json_encode($backtrace) : ""));
			}catch(Exception $error){
				error_log($error->getMessage() . " " . json_encode($error));
				error_log($Message);
			}
		}
	}
?>