<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(str_replace("\\","/",APPPATH).'third_party/fpdf/fpdf.php');

class Pdf extends FPDF {
    public function __construct() {
        parent::__construct();
    }

    var $extgstates;

    public function Header(){
        $this->Image('images/logo_img.jpg',15,5,33);
        $this->SetTextColor(51,102,51);
        $this->SetFont('Times','B',22);
        $this->Cell(50);
        $this->Cell(120,10,'Exportadora Hitalo Agro E.I.R.L.','',0,'C',0);
        $this->Ln(10);
        $this->SetTextColor(184,53,53);
        $this->SetFont('Times','B',14);
        $this->Cell(50);
        $this->Cell(120,10,'Empresa que marca la Diferencia¡¡¡',0,0,'C');        
        //$this->SetAlpha(0.1);
        //$this->Image('images/logo_img.jpg',65,90,100);
        //$this->SetAlpha(1);
    }
    
    public function Footer(){
      $this->SetY(-30);
      $this->SetFont('Arial','B',9);
      $this->SetTextColor(51,102,51);
      $this->Cell(30);      
      $this->Cell(120,7,'______________________________________________________________________________________________________','',0,'C',0);
      $this->Ln(7);

      $this->SetFont('Arial', '', 9);
      $this->Cell(30);      
      $this->Cell(120,4,'Gran Mercado Mayorista de Lima - Pto.: D52, D132  Santa Anita - Lima','',0,'C',0);
      $this->Ln(4);

      $this->Cell(30);      
      $this->Cell(120,4,'Casa: 3493737  Cel.: 999466134  RPM: #680460','',0,'C',0);
      $this->Ln(4);

      $this->Cell(30);      
      $this->Cell(120,4,'E-mail: hitaloagro@gmail.com','',0,'C',0);
      $this->Ln(4);
      //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    /*
    function SetAlpha($alpha, $bm='Normal'){
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms){
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs){
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc(){
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates(){
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            foreach ($this->extgstates[$i]['parms'] as $k=>$v)
                $this->_out('/'.$k.' '.$v);
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresourcedict(){
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_out('>>');
    }

    function _putresources(){
        $this->_putextgstates();
        parent::_putresources();
    }
    */
}