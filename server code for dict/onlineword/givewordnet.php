<?php
$file=fopen("wordnet.json","r");
echo fread($file, filesize("wordnet.json"));

?>