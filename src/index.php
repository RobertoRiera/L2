<?php
include_once 'lib.php';

View::start2("Etnonautas");

$pagina_inicio = strip_tags(file_get_contents('http://orienta.hol.es/pruebas/'));
//$pagina_inicio = file_get_contents('http://orienta.hol.es/pruebas/');

Util::txt($pagina_inicio);

//echo str_word_count($pagina_inicio);
echo $pagina_inicio;

View::end();