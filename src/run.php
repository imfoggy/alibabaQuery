#!/usr/bin/env php
<?php
require 'vendor/autoload.php';
use Foggy\AlibabaQuery;

function is_cli(){
	return preg_match("/cli/i", php_sapi_name()) ? true : false;
}

if(!is_cli()){
	exit('please running it in CLI mode');
}

new AlibabaQuery()->run();