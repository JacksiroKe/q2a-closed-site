<?php
/*
	Plugin Name: Closed Site
	Plugin URI: https://github.com/JackSiro/Q2A-Closed-Site-Plugin/
	Plugin Description: Allows a Q2A site to be locked to allow in registered users only, include support for adverts.
	Plugin Version: 1.5
	Plugin Date: 2014-09-20
	Plugin Author: Jackson Siro
	Plugin Author URI: http://github.com/JackSiro
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: https://github.com/JackSiro/Q2A-Closed-Site-Plugin/master/closed-site/qa-plugin.php

*/

if ( !defined('QA_VERSION') )
{
	header('Location: ../../');
	exit;
	

}	
	
	$plugin_dir = dirname( __FILE__ ) . '/';
	$plugin_url = qa_path_to_root().'qa-plugin/closed-site';

	qa_register_layer('qa-closed-admin.php', 'Closed Site Settings', $plugin_dir , $plugin_url );	
	qa_register_plugin_phrases('qa-closed-lang-*.php', 'qa_closed_lang');
		
	qa_register_plugin_module('page', 'qa-closed.php', 'qa_closed', 'Closed Site');
	qa_register_plugin_layer('qa-closed-layer.php', 'Qa Closed Site');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/
