<?php
include_once 'lib.php';

View::start2("Etnonautas");

$url='http://localhost:63342/Web-de-pruebas-para-el-contador-de-palabras/index.php';

//$pagina_inicio = strip_tags(file_get_contents('$url'));
$pagina_inicio = file_get_contents('http://localhost:63342/Web-de-pruebas-para-el-contador-de-palabras/index.php');

Util::txt($pagina_inicio);

//echo str_word_count($pagina_inicio);
echo $pagina_inicio;

View::end();