<?php
/*
	Plugin Name: Closed Site
	Plugin URI: http://github.com/JackSiro/Closed-Site-Plugin
	Plugin Description: Allows for for locking the site to only logged users only. Premium plugin coming out soon
	Plugin Version: 1.5
	Plugin Date: 2014-09-20
	Plugin Author: Jackson Siro
	Plugin Author URI: http://github.com/JackSiro
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: 

*/

	require_once QA_INCLUDE_DIR.'db/admin.php';
	require_once QA_INCLUDE_DIR.'db/maxima.php';
	require_once QA_INCLUDE_DIR.'db/selects.php';
	require_once QA_INCLUDE_DIR.'app/options.php';
	require_once QA_INCLUDE_DIR.'app/admin.php';

class qa_html_theme_layer extends qa_html_theme_base {
	
	var $plugin_directory;
	var $plugin_url;
	function qa_html_theme_layer($template, $content, $rooturl, $request)
	{
		global $qa_layers;
		$this->plugin_directory = $qa_layers['Closed Site Settings']['directory'];
		$this->plugin_url = $qa_layers['Closed Site Settings']['urltoroot'];
		qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
	}
	
	function nav_list($navigation, $class, $level=null)
	{
		if(qa_opt('closed_site_allow') && $this->template=='admin') {
			if ($class == 'nav-sub') {
				$navigation['closedsite'] = array(
					'label' => qa_lang('qa_closed_lang/closed_site'),
					'url' => qa_path_html('admin/closedsite'),
				);
			}
			
			if($this->request == 'admin/closedsite') {
				$newnav = qa_admin_sub_navigation();
				$navigation = array_merge($newnav, $navigation);
				$navigation['admin']['closedsite'] = true; 
			}
		}
		
		if(count($navigation) > 1 ) qa_html_theme_base::nav_list($navigation, $class, $level=null);	
	}
	
	function head_css() {
		parent::head_css();
		if ($this->template=='closedsite'){
			
			$this->output(
				'<script type="text/javascript">
			var qa_wysiwyg_editor_config = {
				defaultLanguage: \'en\',
				language: \'\'
			};
				var qa_oldonload = window.onload;
				window.onload = function() {
				if (typeof qa_oldonload == \'function\')
					qa_oldonload();

				if (qa_ckeditor_closed_site_page_content = CKEDITOR.replace(\'closed_site_page_content\', qa_wysiwyg_editor_config)) { qa_ckeditor_closed_site_page_content.setData(document.getElementById(\'closed_site_page_content_ckeditor_data\').value); document.getElementById(\'closed_site_page_content_ckeditor_ok\').value = 1; }
			};
		</script>'
			);
			
			$this->output('<script src="../qa-content/qa-admin.js?1.7.4"></script>');
			$this->output('<script src="../qa-plugin/wysiwyg-editor/ckeditor/ckeditor.js?1.7.4"></script>');
		}
	}
	
	function doctype(){
		global $qa_request;
			
		if ( ($qa_request == 'admin/closedsite') and (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) ) {			
			$this->template="closedsite";
			$this->content['navigation']['sub'] = qa_admin_sub_navigation();
			$this->content['navigation']['sub']['closedsite'] = array(
				'label' => qa_lang('qa_closed_lang/closed_site'),  
				'url' => qa_path_html('admin/closedsite'),  
				'selected' => 'selected'); 
			
			$this->content['error']="";
			$this->content['suggest_next']="";
			$this->content['title']= qa_lang_html('admin/admin_title').' - '.qa_lang('qa_closed_lang/closed_site_title');
			
			$saved = qa_clicked('closed_site_save');
			if ($saved) {
				qa_opt('closed_site_allow', (bool)qa_post_text('closed_site_allow'));
				qa_opt('closed_site_page_title', qa_post_text('closed_site_page_title'));
				qa_opt('closed_site_page_content', qa_post_text('closed_site_page_content'));
			}
			
			$options= array(
				'ok' => $saved ? qa_lang('qa_closed_lang/closed_settings_saved') : null,
				'tags' => 'method="post" action="'.qa_path_html(qa_request()).'"',
				'style' => 'tall', 
				'fields' => array(
				
				array(
					'type' => 'checkbox',
					'label' => qa_lang('qa_closed_lang/closed_site_allow'),
					'value' => qa_opt('closed_site_allow'),
					'tags' => 'name="closed_site_allow" id="closed_site_allow"',				
				),
			
				array(
					'type' => 'input',
					'label' => qa_lang('qa_closed_lang/closed_site_page_title'),
					'value' => qa_opt('closed_site_page_title'),
					'tags' => 'name="closed_site_page_title" id="closed_site_page_title"',				
				),
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_page_content'),
					'value' => qa_opt('closed_site_page_content'),
					'rows' => 4,
					'tags' => 'name="closed_site_page_content" id="closed_site_page_content"',				
				),
			
			),
				
				'buttons' => array(
					array(
						'label' => 'Save Changes',
						'tags' => 'name="closed_site_save"',
					),
					
					array(
						'label' => 'Reset to Defaults',
						'tags' =>   'name="qa_closedsite_reset"',
					),
					
				),
			);
			$this->content['form']=$options;
		}
		qa_html_theme_base::doctype();
	}
}
