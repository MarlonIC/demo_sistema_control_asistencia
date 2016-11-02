<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(str_replace("\\","/",APPPATH).'libraries/PHPExcel/PHPExcel.php');
require_once(str_replace("\\","/",APPPATH).'libraries/PHPExcel/PHPExcel/IOFactory.php');
require_once(str_replace("\\","/",APPPATH).'libraries/PHPExcel/PHPExcel/Chart.php');
 
class excel extends PHPExcel { 

    public function __construct() { 
        parent::__construct(); 
    } 
}