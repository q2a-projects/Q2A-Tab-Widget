<?php

	class qa_html_theme_layer extends qa_html_theme_base {
		var $tabs_plugin_url;
		function qa_html_theme_layer($template, $content, $rooturl, $request)
		{
			global $qa_layers;
			$this->tabs_plugin_url = $qa_layers['Tabs Layer']['urltoroot'];
			qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
		}
		
		function head_css() {
			qa_html_theme_base::head_css();
			if (qa_opt('tw_custom_css')!='NO Costume Style')
				$this->output('<link href="' . qa_opt('site_url') . $this->tabs_plugin_url . 'styles/' . qa_opt('tw_custom_css') . '.css" type="text/css" rel="stylesheet"></link>');
		}
		function head_script()
		{
			qa_html_theme_base::head_script();
			$this->output('<script src="' . qa_opt('site_url') . $this->tabs_plugin_url . 'tabs.js"></script>');
			
		}
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/