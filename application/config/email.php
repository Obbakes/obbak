<?php
/*
	if ( ! defined('BASEPATH')) 
		exit('No direct script access allowed');
	
	$config['protocol'] = 'smtp';
	$config['smtp_host'] = 'localhost';
	$config['smtp_port'] = 25;
	$config['smtp_user'] = 'aescudero@localhost';
	$config['smtp_pass'] = 'recbcs84';
	$config['smtp_timeout'] = '15';
	$config['charset']    = 'utf-8';
	$config['crlf']    = "\r\n";
	$config['newline']    = "\r\n";
	$config['mailtype'] = 'html'; // or html
	$config['validation'] = FALSE; // bool whether to validate email or not
	*/

if ( ! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://in.mailjet.com';
    $config['smtp_port'] = 465;
    $config['smtp_user'] = '486e7d25d4e0f73b6e6c7dc14ab39df5';
    $config['smtp_pass'] = 'ae6172e0c905501bbb4e0c56f4595a21';
    $config['smtp_timeout'] = '15';
    $config['charset']    = 'utf-8';
    $config['crlf']    = "\r\n";
    $config['newline']    = "\r\n";
    $config['mailtype'] = 'html'; // or html
    $config['validation'] = TRUE; // bool whether to validate email or not

?>