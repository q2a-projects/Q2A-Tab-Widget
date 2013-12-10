<?php

/*
	Plugin Name: tabs widget
	Plugin URI: 
	Plugin Description: popular & recent questions tab
	Plugin Version: 1.0.0
	Plugin Date: 
	Plugin Author: qa-themes.com
	Plugin Author URI: http://www.qa-themes.com/
	Plugin License: 
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: 
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	qa_register_plugin_layer('qa-tabs-layer.php', 'Tabs Layer');
	qa_register_plugin_module('widget', 'qa-tabs-widget.php', 'qa_tabs_widget', 'Tabs Widget');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/