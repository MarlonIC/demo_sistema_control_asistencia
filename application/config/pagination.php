<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI =& get_instance();

$config['base_url'] = base_url().$CI->router->fetch_class().'/index/page/';
$config["per_page"] = GL_CANTIDAD_PAGINA;
$config['uri_segment'] = 4;
$config['num_links'] = 10;

$config['use_page_numbers'] = TRUE;
$config['cur_tag_open'] = '&nbsp;<a class="current">';
$config['cur_tag_close'] = '</a>';
$config['next_link'] = 'Siguiente';
$config['prev_link'] = 'Anterior';
$config['last_link'] = 'Último';
$config['first_link'] = 'Primero';

/* End of file pagination.php */
/* Location: ./application/config/pagination.php */