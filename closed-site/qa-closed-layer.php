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
	Plugin Update Check URI: https://github.com/JackSiro/Q2A-Closed-Site-Plugin/master/closed-site/qa-plugin.php

*/
require_once QA_INCLUDE_DIR.'qa-theme-base.php';

	class qa_html_theme_layer extends qa_html_theme_base 
	{	
		private $fixed_topbar = false;
			
		function head_css() {
			qa_html_theme_base::head_css();
			if (qa_opt('closed_site_allow')) {
				if (!qa_is_logged_in()) {
					$css = array('<style>');
					$this->output('<link href="' . qa_path_to_root().'qa-plugin/closed-site/qa-style.css" type="text/css" rel="stylesheet"/>');
					$page_background = qa_opt('closed_site_background_url') ? qa_opt('closed_site_background_url') : 'qa-plugin/closed-site/nairobi.jpg';
					if (qa_opt('closed_site_background_allow')) {
						$css[] = 'body{background:url('.$page_background.') no-repeat fixed center 100% !important;}';
						
					} else {
						$css[] = '.qa-closed-left{background: #000;}';	
						$css[] = '.qa-closed-right{border: 1px solid #000;}';	
					}
					$css[] = '</style>';
					$this->output_array($css);
				}
			}
		}
		
		
		function html()
		{
			
			$this->output('<html>');		
			$this->head();			
			$this->output('<body>');
			
			if (qa_opt('closed_site_allow')) {
				if (!qa_is_logged_in()) {
					if (isset($this->content['navigation']['user']['login']) && !QA_FINAL_EXTERNAL_USERS) {
						$login=@$this->content['navigation']['user']['login'];
						if (qa_request_part(0) == 'register') $this->body();					
						else $this->qa_closed_page($login);
						unset($this->content['navigation']['user']['login']);
					}
				}
				else $this->body();
				
			} else $this->body();
			
			$this->output('</body>');
			$this->output('</html>');
		}
		public function qa_closed_header()
		{
			$class = $this->fixed_topbar ? ' fixed' : '';
			$this->output('<div id="qam-topbar" class="clearfix' . $class . '">');
			$this->output('<div class="qam-main-nav-wrapper clearfix">');
			$this->nav_user_search();
			$this->logo();
			$this->output('</div>');
		}
		
		function qa_closed_page($login)
		{
			$this->body_prefix();
			$this->notices();			
			$this->qa_closed_header();

			$this->output('<div class="qa-body-wrapper">', '');
			$this->output(qa_opt('closed_site_html_top'), '<!-- Html Top -->');
			
			$this->output('<div class="qa-main-wrapper">', '');
			
			$this->qa_closed_right();
			$this->output('<div class="qa-closed-right">');
			
			$this->qa_closed_left($login);
			$this->output('</div>', '');
			
			$this->output('</div>');
			$this->output(qa_opt('closed_site_html_bottom'), '<!-- Html Bottom -->');

			$this->output('</div>');

			$this->footer();
			$this->body_suffix();
		}
		
		function qa_closed_right()
		{
			$page_title = qa_opt('closed_site_page_title') ? qa_opt('closed_site_page_title') : qa_lang('qa_closed_lang/default_page_title');
			$page_content = qa_opt('closed_site_page_content') ? qa_opt('closed_site_page_content') : qa_lang('qa_closed_lang/default_page_content');
			
			$this->output('<div class="qa-closed-left">');
			$this->output('<h1 class="closed-page-title">'.$page_title.'</h1>');
			
			$this->output(qa_opt('closed_site_html_left1'), '<!-- Html 1 Left -->');
			$this->output('<div class="closed-page-content">'.$page_content.'</div>');
			$this->output(qa_opt('closed_site_html_left2'), '<!-- Html 2 Left -->');
			
			$this->output('</div>');
		}
		
		function qa_closed_left($login)
		{
			$login_title = qa_opt('closed-site-login-title') ? qa_opt('closed_site_login_title') : qa_lang('qa_closed_lang/default_login_title');
			$login_content = qa_opt('closed-site-login-content') ? qa_opt('closed_site_login_content') : qa_lang('qa_closed_lang/default_login_content');
			$this->output('<div class="qa-closed-right-items">');
			$this->output('<span class="closed-page-title">'.$login_title.'</span><br><div class="closed_page_content_l">'.$login_content.'</div>');
			
			if (isset($this->content['error'])) $this->error($this->content['error']);
			$this->output(qa_opt('closed_site_html_right1'), '<!-- Html 1 Right -->');
			$this->output(
				'<form action="' . $login['url'] . '" method="post">',
					'<input type="text" name="emailhandle" dir="auto" placeholder="' . trim(qa_lang_html(qa_opt('allow_login_email_only') ? 'users/email_label' : 'users/email_handle_label'), ':') . '"/>',
					'<input type="password" name="password" dir="auto" placeholder="' . trim(qa_lang_html('users/password_label'), ':') . '"/>',
					'<div><input type="checkbox" name="remember" id="qam-rememberme" value="1"/>',
					'<label for="qam-rememberme">' . qa_lang_html('users/remember') . '</label></div>',
					'<input type="hidden" name="code" value="' . qa_html(qa_get_form_security_code('login')) . '"/>',
					'<input type="submit" value="' . $login['label'] . '" class="qa-form-tall-button qa-form-tall-button-login" name="dologin"/>',
				'</form><br>'
			);
			$this->output('<a class="qa-form-tall-button qa-form-tall-button-login" href="register">Register</a>');
			$this->output('<br><br>');
			
			$this->output(qa_opt('closed_site_html_right2'), '<!-- Html 2 Right -->');			
			$this->output('</div>');
		}
		
	}
?>
