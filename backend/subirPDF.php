<?php
header('Content-Type: application/json');
include 'clasesPDF.php-dist';
require 'vendor/autoload.php';
include 'stemm_es.php';

$archivo = "archivos\ADA5_PatCituk DavidAlberto.pdf";
$solr ="http://localhost:8983/solr/BRIW/update/?commit=true";

$parser = new Smalot\PdfParser\Parser();
$pdf = $parser->parseFile($archivo);
$texto = $pdf->getText();

use voku\helper\StopWords;
$texto = StopWords($texto);


use ICanBoogie\Inflector;
$inflector = Inflector::get('es');
$texto = preg_replace('/\s+/', ' ', preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚ\s]+/u', '', $texto));
$tokens = explode(' ', $texto);

$normalizado= [];
$stemer = new stemm_es;
foreach($tokens as $token){
    //$stem = $stemer->stemm($token);
    $normal=  $inflector->humanize($token); 
    if(!array_key_exists($normal, $normalizado)){
        $normalizado[$normal] = 1;
    }else{
        $normalizado[$normal]+= 1;
    }
}
arsort($normalizado);


$palabrasClave = array_slice($normalizado, 0, 20);
$palabrasClave = array_keys($palabrasClave);


$resultado;
eval('$resultado=' . `exiftool -php -q "$archivo"`);
$resultado = $resultado[0];
$resultado["palabrasClave"] = $palabrasClave;
$resultado["contenido"] = mb_convert_encoding(substr($texto, 0, 500), "UTF-8"); //SHINANIGANS
//echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
$json = json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
echo apiPost($solr, $json);

//var_dump($resultado);
//echo json_encode( $resultado, JSON_UNESCAPED_UNICODE);
//$json =;
//echo $json;




function StopWords($string){
    $listaPalabras = new StopWords();
    $palabras = $listaPalabras->getStopWordsFromLanguage('es');
    return preg_replace('/\b('.implode('|',$palabras).')\b/','',$string);
}

function apiPost($url,$content){
    $ch = curl_init($url);
    $header = array("Content-type:application/json","charset=utf-8");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "[$content]");
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $json_response = curl_exec($ch);
    curl_close($ch);
    return $json_response;
}
?>
