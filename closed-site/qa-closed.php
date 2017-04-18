<?php
/*
	Plugin Name: Closed Site
	Plugin URI: https://github.com/JackSiro/Q2A-Closed-Site-Plugin/
	Plugin Description: Allows for for locking the site to only logged users only. Premium plugin coming out soon
	Plugin Version: 1.5
	Plugin Date: 2014-09-20
	Plugin Author: Jackson Siro
	Plugin Author URI: http://github.com/JackSiro
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: 

*/
require_once QA_INCLUDE_DIR.'app/format.php';
require_once QA_INCLUDE_DIR.'app/limits.php';
require_once QA_INCLUDE_DIR.'db/selects.php';
require_once QA_INCLUDE_DIR.'util/sort.php';

class qa_closed
{
	private $directory;
	private $urltoroot;


	public function load_module($directory, $urltoroot)
	{
		$this->directory = $directory;
		$this->urltoroot = $urltoroot;
	}

	public function match_request( $request )
	{
		return $request == 'closed';
	}
	
	function admin_form(&$qa_content)
		{
			$saved = qa_clicked('closed_site_plugin_save_button');

			if ($saved)
				qa_opt('closed_site_allow', (int) qa_post_text('closed_site_allow_field'));
			
			qa_set_display_rules($qa_content, array(
				'closed_site_allow_display' => 'closed_site_allow_field',
			));
			return array(
				'ok' => $saved ? qa_lang('qa_closed_lang/closed_settings_saved') : null,
				
				'fields' => array(
					array(
						'label' => qa_lang('qa_closed_lang/closed_site_allow'),
						'type' => 'checkbox',
						'value' => qa_opt('closed_site_allow'),
						'tags' => 'name="closed_site_allow_field" id="closed_site_allow_field"',
					),
					array(
						'id' => 'closed_site_allow_display',
						'type' => 'custom',
						'html' => '<p>'.qa_lang('qa_closed_lang/closed_site_allow_explanation').'</p>',
					),
				),
				
				'buttons' => array(
					array(
						'label' => qa_lang('qa_closed_lang/save_changes'),
						'tags' => 'name="closed_site_plugin_save_button"',
					),
				),
			);
		}
	
	

}
