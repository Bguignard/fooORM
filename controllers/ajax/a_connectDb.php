<?php
/**
 * Created by Bruno Guignard
 */
include_once '../../tools/Tools.php';
include_once '../../class/Connexion.php';
include_once '../../class/ClassMaker.php';

$databaseName = Tools::getPost('databaseName', 'string');
$databaseUserName = Tools::getPost('databaseUserName', 'string');
$databasePassword = Tools::getPost('databasePassword', 'string');

$cm = new ClassMaker($databaseName, $databaseUserName, $databasePassword);

echo $cm->makeFiles();