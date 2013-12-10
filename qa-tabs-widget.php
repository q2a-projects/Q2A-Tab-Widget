<?php

class qa_tabs_widget {
	private $directory;
	public function load_module( $directory, $urltoroot )
	{
		$this->directory = $directory;
	}
	function option_default($option)
	{
		switch ($option) {
			case 'tw_custom_css': return 'default';
			case 'tw_custom_css_index': return 1;
			case 'tw_popular_number': return 6;
			case 'tw_recent_number': return 6;
			case 'tw_recent_lable': return 'Recent';
			case 'tw_popular_lable': return 'Popular';
			case 'tw_ads_pos': return 3;
		}
	}
	function admin_form(&$qa_content)
	{
	
		// load styles
		$real_plugin_path = $this->directory;
		$s_path = $real_plugin_path . 'styles';
		
		if ((is_dir($s_path))==false)
		{
			//in case it could not read directory content try this to get absolute & real path of directories
			$site_path = getcwd();
		
			$path = $real_plugin_path;
			$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
			$parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
			$absolutes = array();
			foreach ($parts as $part) {
				if ('.' == $part) continue;
				if ('..' == $part) {
					array_pop($absolutes);
				} else {
					$absolutes[] = $part;
				}
			}
			$plugin_path= implode(DIRECTORY_SEPARATOR, $absolutes);
			$real_plugin_path = $site_path . '/' . $plugin_path;
			$s_path = $real_plugin_path . '/styles';
		}
		// List of styles
		$styles=array();
		$files = scandir($s_path, 1);
		$styles[]="NO Costume Style";
		foreach ($files as $file) 
			if (!((empty($file)) or($file=='.') or ($file=='..')))
				$styles[] = preg_replace("/\\.[^.]*$/", "", $file);		
	
	
		$saved=false;
		
		if (qa_clicked('tw_save_button')) {
			qa_opt('tw_custom_css', $styles[(int)qa_post_text('tw_custom_css')]);
			qa_opt('tw_custom_css_index', (int)qa_post_text('tw_custom_css'));
			qa_opt('tw_thumbnail_enable', (int)qa_post_text('tw_thumbnail_enable'));
			qa_opt('tw_thumbnail', qa_post_text('tw_thumbnail'));
			qa_opt('tw_popular_number', (int)qa_post_text('tw_popular_number'));
			qa_opt('tw_recent_number', (int)qa_post_text('tw_recent_number'));
			qa_opt('tw_recent_lable', qa_post_text('tw_recent_lable'));
			qa_opt('tw_popular_lable', qa_post_text('tw_popular_lable'));
			qa_opt('tw_ads', qa_post_text('tw_ads_field'));
			qa_opt('tw_ads_pos', (int)qa_post_text('tw_ads_pos_field'));
			$saved=true;
		}
		qa_set_display_rules($qa_content, array(
			'tw_thumbnail' => 'tw_thumbnail_enable',
		));
		return array(
			'ok' => $saved ? 'Tag cloud settings saved' : null,
			
			'fields' => array(
				array(
					'label' => 'CSS Styles',
					'tags' => 'NAME="tw_custom_css" ID="tw_custom_css"',
					'type' => 'select',
					'options' => @$styles,
					'value' => @$styles[qa_opt('tw_custom_css_index')],
				),
				array(
					'label' => 'Enable thumbnail image',
					'type' => 'checkbox',
					'value' => qa_opt('tw_thumbnail_enable'),
					'tags' => 'NAME="tw_thumbnail_enable" ID="tw_thumbnail_enable"',
					'note' => 'read first image inside question content and show it as thumbnail image',
				),
				array(
					'label' => 'Default thumbnail image URL',
					'value' => qa_opt('tw_thumbnail'),
					'tags' => 'NAME="tw_thumbnail" ID="tw_thumbnail"',
					'suffix' => 'leave empty to load no thumbnails',
					'id' => 'tw_thumbnail',
					'tags' => 'name="tw_thumbnail"',
				),
				array(
					'label' => 'Lable of popular questions:',
					'value' => qa_opt('tw_popular_lable'),
					'tags' => 'NAME="tw_popular_lable" ID="tw_popular_lable"',
				),		
				array(
					'label' => 'Number of popular questions:',
					'type' => 'number',
					'value' => (int)qa_opt('tw_popular_number'),
					'suffix' => 'tags',
					'tags' => 'name="tw_popular_number"',
				),
				array(
					'label' => 'Lable of recent questions:',
					'value' => qa_opt('tw_recent_lable'),
					'tags' => 'NAME="tw_recent_lable" ID="tw_recent_lable"',
				),		
				array(
					'label' => 'Number of recent questions:',
					'type' => 'number',
					'value' => (int)qa_opt('tw_recent_number'),
					'tags' => 'name="tw_recent_number"',
				),
				array(
					'label' => 'Advertisment box',
					'type' => 'textbox',
					'value' => qa_opt('tw_ads'),
					'tags' => 'NAME="tw_ads_field" ID="tw_ads_field"',
					'suffix' => 'HTML is supported',
					'rows' => 3,
				),
				array(
					'label' => 'Advertisment position',
					'value' => qa_opt('tw_ads_pos'),
					'tags' => 'NAME="tw_ads_pos_field" ID="tw_ads_pos_field"',
					'suffix' => 'Show Advertisment box after this number of questions',
				),
			),
			
			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'name="tw_save_button"',
				),
			),
		);
	}

	
	function allow_template($template)
	{
		$allow=false;
		switch ($template)
		{
			case 'activity':
			case 'qa':
			case 'questions':
			case 'hot':
			case 'ask':
			case 'categories':
			case 'question':
			case 'tag':
			case 'tags':
			case 'unanswered':
			case 'user':
			case 'users':
			case 'search':
			case 'account':
			case 'updates':
			case 'admin':
			case 'custom':
				$allow=true;
				break;
		}
		
		return $allow;
	}

	
	function allow_region($region)
	{
		return ($region=='side');
	}
	

	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		$AdsPos = (int)qa_opt('tw_ads_pos');
		require_once QA_INCLUDE_DIR.'qa-db-selects.php';
		require_once QA_INCLUDE_DIR.'qa-app-format.php';
		require_once QA_INCLUDE_DIR.'qa-app-q-list.php';
		$categoryslugs=qa_request_parts(1);
		$countslugs=count($categoryslugs);
		
		$sort='created';
		$userid=qa_get_logged_in_userid();
		$questions = qa_db_select_with_pending(
			qa_db_qs_selectspec($userid, 'created', 0, '', null, false, false, (int)qa_opt('tw_recent_number'))
		);	
		$hotquestions =qa_db_select_with_pending(
			qa_db_qs_selectspec($userid, 'hotness', 0, '', null, false, false, (int)qa_opt('tw_popular_number'))
		);
		
		$themeobject->output(
			'
			  <ul class="tabs">
				<li><a href="#tab1">' . qa_opt('tw_popular_lable') . '</a></li>
				<li><a href="#tab2">' . qa_opt('tw_recent_lable') . '</a></li>
			  </ul>
			  <div class="tab-content" id="tab1">
				<ul class="popular">
			'
		);
		$thumb='';
		$i=0;
		foreach ($hotquestions as $question)
		{
			$i++;
			$questionid=$question['postid'];
			$questionlink = qa_path_html(qa_q_request($questionid, $question['title']),null, qa_opt('site_url'));
			$q_time= qa_when_to_html($question['created'], 7);
			$when=@$q_time['prefix'] . ' ' . @$q_time['data'] . ' ' . @$q_time['suffix'];
			// get question content
			$result=qa_db_query_sub('SELECT content FROM ^posts WHERE postid=#', $questionid);
			$postinfo=qa_db_read_one_assoc($result, 'postid');
			if (qa_opt('tw_thumbnail_enable')){
				// get thumbnail
				$doc = new DOMDocument();
				@$doc->loadHTML($postinfo['content']);
				$xpath = new DOMXPath($doc);
				$src = $xpath->evaluate("string(//img/@src)");
				
				$default_thumbnail=qa_opt('tw_thumbnail');
				if ( empty($src) && !empty($default_thumbnail) )
					$src = qa_opt('tw_thumbnail');
				$thumb='';
				if ( !empty($src) )
					$thumb= '<div class="tab-div-thumb"><a class="tab-link-thumbnail" href="' . $questionlink . '"><img class="tab-thumbnail" width="60" height="50" src="' . $src . '"></a></div>';
			}
			$themeobject->output('<li>' . $thumb);
			$themeobject->output('<div class="tab-div-body"><a class="tab-link" href="' . $questionlink . '"><h4 class="tab-link-header">' . $question['title'] . '</h4>');
			$themeobject->output('<span class="tab-time">' . $when . '</span></a></div></li>');
			$ads_content = qa_opt('tw_ads');
			if( ($i==$AdsPos) && !empty($ads_content))
				$themeobject->output('<div class="tab-ads">' . qa_opt('tw_ads') . '</div>');
		}
		$themeobject->output(
			'
			  </ul>
			  </div>
			  <div class="tab-content" id="tab2" style="display: none;">
			  <ul class="recent">
			'
		);
		
		$i=0;
		foreach ($questions as $question)
		{
			$i++;
			$questionid=$question['postid'];
			$questionlink = qa_path_html(qa_q_request($questionid, $question['title']),null, qa_opt('site_url'));
			$q_time= qa_when_to_html($question['created'], 7);
			$when=@$q_time['prefix'] . ' ' . @$q_time['data'] . ' ' . @$q_time['suffix'];
			// get question content
			$result=qa_db_query_sub('SELECT content FROM ^posts WHERE postid=#', $questionid);
			$postinfo=qa_db_read_one_assoc($result, 'postid');
			if (qa_opt('tw_thumbnail_enable')){
				// get thumbnail
				$doc = new DOMDocument();
				@$doc->loadHTML($postinfo['content']);
				$xpath = new DOMXPath($doc);
				$src = $xpath->evaluate("string(//img/@src)");
				
				$default_thumbnail=qa_opt('tw_thumbnail');
				if ( empty($src) && !empty($default_thumbnail) )
					$src = qa_opt('tw_thumbnail');
				$thumb='';
				if ( !empty($src) )
					$thumb= '<div class="tab-div-thumb"><a class="tab-link-thumbnail" href="' . $questionlink . '"><img class="tab-thumbnail" width="60" height="50" src="' . $src . '"></a></div>';
			}
			$themeobject->output('<li>' . $thumb);
			$themeobject->output('<div class="tab-div-body"><a class="tab-link" href="' . $questionlink . '"><h4 class="tab-link-header">' . $question['title'] . '</h4>');
			$themeobject->output('<span class="tab-time">' . $when . '</span></a></div></li>');
			$ads_content = qa_opt('tw_ads');
			if( ($i==$AdsPos) && !empty($ads_content))
				$themeobject->output('<div class="tab-ads">' . qa_opt('tw_ads') . '</div>');
		}		
		$themeobject->output(
			'
			  </ul>
			  </div>
			'
		);
		
	}
}


/*
	Omit PHP closing tag to help avoid accidental output
*/