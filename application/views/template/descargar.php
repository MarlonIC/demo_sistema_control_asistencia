<?php
	if(!empty($download_path)){
        if(get_http_response_code(base_url().'documentos/'.$download_path) != "404"){
            $data = file_get_contents(base_url().'documentos/'.$download_path);
            // Descargamos el documento excel
			force_download($download_path, $data);
			// Eliminamos el documento excel
			$full_path = FCPATH.'documentos\\'.$download_path;
			unlink($full_path);
        }
    }
?>