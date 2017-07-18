<?php

global $gotcha;

$gotcha['libs']	= plugin_dir_path(__FILE__).'/libs/';
$gotcha['layout']	= plugin_dir_path(__FILE__).'/layout/';

// setting

include($gotcha['admin'].	'init.php');
include($gotcha['admin'].	'validate.php');
include($gotcha['admin'].	'ajax.php');

include($gotcha['libs'].	'index.php');
include($gotcha['admin'].	'setting.php');
?>