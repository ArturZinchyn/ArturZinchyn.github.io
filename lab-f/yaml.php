<?php // I:\ptw\lab-f\yaml.php
$data = [
    'name' => 'Artur Zinchyn',
    'index' => '57744',
    'date' => date(DATE_ATOM),
];

$yaml = yaml_emit($data);

echo $yaml;
