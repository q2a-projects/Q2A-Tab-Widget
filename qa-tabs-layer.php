<?php

	class qa_html_theme_layer extends qa_html_theme_base {

		function head_css() {
			qa_html_theme_base::head_css();
			if (qa_opt('tw_custom_css')!='NO Costume Style')
				$this->output('<link href="' . qa_opt('site_url'). QA_HTML_THEME_LAYER_URLTOROOT . 'styles/' . qa_opt('tw_custom_css') . '.css" type="text/css" rel="stylesheet"></link>');
		}
		function head_script()
		{
			qa_html_theme_base::head_script();
			$this->output('<script src="' . qa_opt('site_url'). QA_HTML_THEME_LAYER_URLTOROOT .'tabs.js"></script>');
			
		}
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/