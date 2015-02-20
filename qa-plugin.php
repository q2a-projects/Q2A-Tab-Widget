<?php

/*
	Plugin Name: Tabs Widget
	Plugin URI: https://github.com/Towhidn/Q2A-Tab-Widget
	Plugin Description: Popular & Recent questions tab
	Plugin Version: 1.1
	Plugin Date: 
	Plugin Author: QA-Themes.com
	Plugin Author URI: http://qa-themes.com/
	Plugin License: GPLv3+
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: https://raw.github.com/Towhidn/Q2A-Tab-Widget/master/qa-plugin.php
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