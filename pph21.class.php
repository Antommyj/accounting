<?php

/**
 * Created by PhpStorm.
 * User: antommylim
 * Date: 2/20/17
 * Time: 12:06 PM
 */
class pph21
{
    private $gaji;
    private $thr;
    public $tangungan;
    public $kawin;
    public $errorWarning = array();
    public $nonTax;
    public $includeThr;

    function __construct($gaji = 0,$thr = 0, $tanggungan, $kawin)
    {
        if (is_numeric($gaji) && $gaji >= 0){
            $this->gaji = $gaji;
        }else{
            $this->errorWarning[] = "Invalid value for variable gaji";
        }

        if(is_numeric($tanggungan) && $tanggungan >= 0){
            $this->tangungan = $tanggungan;
        }else{
            $this->errorWarning[] = "Invalid value for variable tanggungan";
        }

        if(is_bool($kawin)){
            $this->kawin = $kawin;
        }else{
            $this->errorWarning[] = "Tidak diketahui status perkawinan";
        }

        if(($this->PKP() - $this->PTKP()) > 0){
            $this->nonTax = FALSE;
        }else{
            $this->nonTax = TRUE;
        }

        $this->thr = $thr;
    }

    public function setIncludeThr($bool){
        $this->includeThr = $bool;
    }

    public function penghasilan_bruto(){
        if($this->includeThr ==  TRUE){
            return ($this->gaji*12) + $this->thr;
        }else{
            return $this->gaji*12;
        }
    }

    public function biaya_jabatan(){
        $biaya = 0.05 * $this->penghasilan_bruto();
        if($biaya < 6000000){
            return $biaya;
        }else{
            return 6000000;
        }
    }


    public function PTKP(){
        if($this->kawin == FALSE){
            switch($this->tangungan){
                case 0: $ptkp = 54000000;
                    break;
                case 1: $ptkp = 58500000;
                    break;
                case 2: $ptkp = 63000000;
                    break;
                case ($this->tangungan >= 3): $ptkp = 67500000;
                    break;
            }
        }elseif($this->kawin == TRUE){
            switch($this->tangungan){
                case 0: $ptkp = 58500000;
                    break;
                case 1: $ptkp = 63000000;
                    break;
                case 2: $ptkp = 67500000;
                    break;
                case ($this->tangungan >= 3): $ptkp = 72000000;
                    break;
            }
        }
        return $ptkp;
    }

    public function netto(){
        $netto = $this->penghasilan_bruto() - $this->biaya_jabatan();
        return $netto;
    }

    public function PKP(){
        $pkp = $this->netto() - $this->PTKP();
        if($pkp > 0){
            return $pkp;
        }else{
            return 0;
        }
    }

    public function tarif17()
    {
        $g = $this->PKP();
        $r = "";
        if ($g <= 50000000) {
            $r = array((0.05 * $g), "I");
        } elseif ($g > 50000000 && $g <= 250000000) {
            $r = array(((0.15 * $g) - 5000000), "II");
        } elseif ($g > 250000000 && $g <= 500000000) {
            $r = array(((0.25 * $g) - 30000000), "III");
        } elseif ($g > 500000000) {
            $r = array(((0.3 * $g) - 55000000), "IV");
        }
        return $r;
    }

}
