<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class nusoap{

	function Nusoap(){
		require_once(str_replace("\\","/",APPPATH).'libraries/NuSOAP/lib/nusoap'.EXT);
	}
	
}
?>