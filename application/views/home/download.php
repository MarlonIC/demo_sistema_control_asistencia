<?php
    if(!empty($download_path)){
        if(get_http_response_code(base_url().'documentos/'.$download_path) != "404"){
            $data = file_get_contents(base_url().'documentos/'.$download_path);
            $name = $download_path; 
            force_download($name, $data);
        }
    }
?>