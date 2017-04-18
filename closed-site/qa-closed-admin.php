<?php
/*
	Plugin Name: Closed Site
	Plugin URI: https://github.com/JackSiro/Q2A-Closed-Site-Plugin/
	Plugin Description: Allows for for locking the site to only logged users only.
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

				if (qa_ckeditor_closed_site_page_content_field = CKEDITOR.replace(\'closed_site_page_content_field\', qa_wysiwyg_editor_config)) { qa_ckeditor_closed_site_page_content_field.setData(document.getElementById(\'closed_site_page_content_field_ckeditor_data\').value); document.getElementById(\'closed_site_page_content_field_ckeditor_ok\').value = 1; }
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
			$reset = qa_clicked('closed_site_reset');
			if ($saved) {
				qa_opt('closed_site_allow', (bool)qa_post_text('closed_site_allow_field'));
				qa_opt('closed_site_page_title', qa_post_text('closed_site_page_title_field'));
				qa_opt('closed_site_page_content', qa_post_text('closed_site_page_content_field'));
				qa_opt('closed_site_login_title', qa_post_text('closed_site_login_title_field'));
				qa_opt('closed_site_login_content', qa_post_text('closed_site_login_content_field'));
				qa_opt('closed_site_background_allow', (bool)qa_post_text('closed_site_background_allow_field'));
				qa_opt('closed_site_background_url', qa_post_text('closed_site_background_url_field'));
				qa_opt('closed_site_html_top', qa_post_text('closed_site_html_top_field'));
				qa_opt('closed_site_html_left1', qa_post_text('closed_site_html_left1_field'));
				qa_opt('closed_site_html_left2', qa_post_text('closed_site_html_left2_field'));
				qa_opt('closed_site_html_right1', qa_post_text('closed_site_html_right1_field'));
				qa_opt('closed_site_html_right2', qa_post_text('closed_site_html_right2_field'));
				qa_opt('closed_site_html_bottom', qa_post_text('closed_site_html_bottom_field'));
			}
			
			if ($reset) {
				qa_opt('closed_site_allow', '');
				qa_opt('closed_site_page_title', '');
				qa_opt('closed_site_page_content', '');
				qa_opt('closed_site_login_title', '');
				qa_opt('closed_site_login_content', '');
				qa_opt('closed_site_background_allow', '');
				qa_opt('closed_site_background_url', '');
				qa_opt('closed_site_html_top', '');
				qa_opt('closed_site_html_left1', '');
				qa_opt('closed_site_html_left2', '');
				qa_opt('closed_site_html_right1', '');
				qa_opt('closed_site_html_right2', '');
				qa_opt('closed_site_html_bottom', '');
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
					'tags' => 'name="closed_site_allow_field" id="closed_site_allow_field"',				
				),
			
				array(
					'type' => 'input',
					'label' => qa_lang('qa_closed_lang/closed_site_page_title'),
					'value' => qa_opt('closed_site_page_title'),
					'tags' => 'name="closed_site_page_title_field" id="closed_site_page_title_field"',				
				),
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_page_content'),
					'value' => qa_opt('closed_site_page_content'),
					'rows' => 7,
					'tags' => 'name="closed_site_page_content_field" id="closed_site_page_content_field"',				
				),
			
				array(
					'type' => 'input',
					'label' => qa_lang('qa_closed_lang/closed_site_login_title'),
					'value' => qa_opt('closed_site_login_title'),
					'tags' => 'name="closed_site_login_title_field" id="closed_site_login_title_field"',				
				),
				
				array(
					'type' => 'input',
					'label' => qa_lang('qa_closed_lang/closed_site_login_content'),
					'value' => qa_opt('closed_site_login_content'),
					'tags' => 'name="closed_site_login_content_field" id="closed_site_login_content_field"',				
				),
				
				array(
					'type' => 'checkbox',
					'label' => qa_lang('qa_closed_lang/closed_site_background_allow'),
					'value' => qa_opt('closed_site_background_allow'),
					'tags' => 'name="closed_site_allow_field" id="closed_sitebackground__allow_field"',				
				),
			
				array(
					'type' => 'input',
					'label' => qa_lang('qa_closed_lang/closed_site_background_url'),
					'value' => qa_opt('closed_site_background_url'),
					'tags' => 'name="closed_site_background_url_field" id="closed_site_background_url_field"',				
				),
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_html_top'),
					'value' => qa_opt('closed_site_html_top'),
					'rows' => 7,
					'tags' => 'name="closed_site_html_top_field" id="closed_site_html_top_field"',				
				),
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_html_left1'),
					'value' => qa_opt('closed_site_html_left1'),
					'rows' => 7,
					'tags' => 'name="closed_site_html_left1_field" id="closed_site_html_left1_field"',				
				),
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_html_left2'),
					'value' => qa_opt('closed_site_html_left2'),
					'rows' => 7,
					'tags' => 'name="closed_site_html_left2_field" id="closed_site_html_left2_field"',				
				),
				
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_html_right1'),
					'value' => qa_opt('closed_site_html_right1'),
					'rows' => 7,
					'tags' => 'name="closed_site_html_right1_field" id="closed_site_html_right1_field"',				
				),
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_html_right2'),
					'value' => qa_opt('closed_site_html_right2'),
					'rows' => 7,
					'tags' => 'name="closed_site_html_right2_field" id="closed_site_html_right2_field"',				
				),
				array(
					'type' => 'textarea',
					'label' => qa_lang('qa_closed_lang/closed_site_html_bottom'),
					'value' => qa_opt('closed_site_html_bottom'),
					'rows' => 7,
					'tags' => 'name="closed_site_html_bottom_field" id="closed_site_html_bottom_field"',				
				),
				
			),
				
				'buttons' => array(
					array(
						'label' => qa_lang('qa_closed_lang/save_changes'),
						'tags' => 'name="closed_site_save"',
					),
					
					array(
						'label' => qa_lang('qa_closed_lang/reset_changes'),
						'tags' =>   'name="closed_site_reset" onclick="return confirm('.qa_js(qa_lang_html('admin/reset_options_confirm')).');"',
					),
					
				),
			);
			$this->content['form']=$options;
			$this->content['custom']= '<p>If you think this plugin is great and helps you on your site please donate some $5 to $30 to my paypal account: <a href="mailto:smataweb@gmail.com">smataweb@gmail.com</a></p>';
		}
		qa_html_theme_base::doctype();
	}
}
