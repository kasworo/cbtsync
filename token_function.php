<?php
    define("BASEPATH", dirname(__FILE__));
    function getToken($hrf)
    { 
        $kar= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $jkar= strlen($kar); 
        $jkar--; 
        $token=NULL; 
        for($x=1;$x<=$hrf;$x++){ 
            $pos = rand(0,$jkar); 
            $token .= substr($kar,$pos,1); 
        }
        return $token; 
    }
?>