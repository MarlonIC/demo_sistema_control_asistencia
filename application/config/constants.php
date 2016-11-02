<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
*/
define('GL_CODIGO_APLICACION', 1);
define('GL_CORREO_SERVIDOR', '');
define('GL_CORREO_USUARIO', '');
define('GL_CORREO_CONTRASENA', '');
define('GL_MONEDA_LOCAL', 'S/.');
define('GL_CANTIDAD_PAGINA', 50);
define('GL_CANTIDAD_PAGINA1', 100);
define('GL_CANTIDAD_PAGINA2', 200);
define('GL_PERMISO_ACCION', 1);

define('ENT_SISTEMA', 1);

define('ACC_ACCESO_AL_SISTEMA', 1);
define('ACC_CERRAR_SESION', 2);
define('ACC_ACCESO_DENEGADO', 3);
define('ACC_CONSULTA_NO_PERMITIDA', 4);
define('ACC_CONSULTA_NO_EXISTE', 5);
define('ACC_NUEVO', 6);
define('ACC_MODIFICACION', 7);
define('ACC_ELIMINACION', 8);
define('ACC_CONSULTA', 9);
define('ACC_REPORTE', 10);
define('ACC_DETALLE', 11);
define('ACC_ERROR_VALIDACION', 12);


/* End of file constants.php */
/* Location: ./application/config/constants.php */