<?php
/**
 * Created by Bruno Guignard
 */
include_once '../../tools/Tools.php';
$databaseName = Tools::getPost('databaseName', 'string');
$databaseUserName = Tools::getPost('databaseUserName', 'string');
$databasePassword = Tools::getPost('databasePassword', 'string');

echo $databaseName . ' ' . $databaseUserName . ' ' . $databasePassword;