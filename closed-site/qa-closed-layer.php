<?php
/*
	Plugin Name: Closed Site
	Plugin URI: http://github.com/JackSiro/Closed-Site-Plugin
	Plugin Description: Allows for for locking the site to only logged users only.
	Plugin Version: 1.5
	Plugin Date: 2014-09-20
	Plugin Author: Jackson Siro
	Plugin Author URI: http://github.com/JackSiro
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI:

*/
require_once QA_INCLUDE_DIR.'qa-theme-base.php';

	class qa_html_theme_layer extends qa_html_theme_base 
	{	
		
		function head_css() {
			qa_html_theme_base::head_css();
			if (qa_opt('closed_site_allow')) {
				if (!qa_is_logged_in())
					$this->output('<link href="' . qa_path_to_root().'qa-plugin/closed-site/qa-style.css" type="text/css" rel="stylesheet"/>');
			}
		}
		
		
		function html()
		{
			$this->output('<html>');		
			$this->head();			
			$this->output('<body>');
			
			if (qa_opt('closed_site_allow')) {
				if (!qa_is_logged_in()) {
					if (qa_request_part(0) == 'register') $this->body();					
					else $this->qa_login();						
					}
				else $this->body();
			} else $this->body();
			
			$this->output('</body>');
			$this->output('</html>');
		}
		
		function qa_login()
		{
						
			$this->output('<div class="qa_page_wrap">');
			$this->output('<div id="qa_login_group">',
					'<a class="qa-logo" href=".">'.
			$this->content['site_title'].'</a>');			
			$this->login();	
			$this->output('</div>');
			$this->output('<div class="qa_loginpage">');
			$this->output('<div class="qa_main">');
			$this->qa_main();
			$this->output('</div>');
			$this->output('<div class="qa_left">');
			$this->register();
			$this->output('</div>');
			$this->output('</div>');
			$this->output('</div>');
			
				
		}
		function login(){
			if (!qa_is_logged_in()) {
				$login=@$this->content['navigation']['user']['login'];

				if (isset($login) && !QA_FINAL_EXTERNAL_USERS) {
				
				//$this->output('');

					unset($this->content['navigation']['user']['login']); // removes regular navigation link to log in page
					}
				}
			}
			
			function qa_main()
			{
				$page_title = qa_opt('closed_site_page_title') ? qa_opt('closed_site_page_title') : qa_lang('qa_closed_lang/default_login_title');
				$page_content = qa_opt('closed_site_page_content') ? qa_opt('closed_site_page_content') : qa_lang('qa_closed_lang/default_login_content');
				$this->output('<h1 style="font-size:36px;">'.$page_title.'</h1>'.$page_content);
				
			}
			
		    function register()
			{
				$login=@$this->content['navigation']['user']['login'];

				$this->output('
				<span style="font-size: 36px">Sign Up</span><br>
					<div>It’s free and always will be.</div>
						<form id="qa_loginform" action="'.$login['url'].'" method="post">',
							'<input type="text" id="qa_userid" name="emailhandle" placeholder="'.trim(qa_lang_html(qa_opt('allow_login_email_only') ? 'users/email_label' : 'users/email_handle_label'), ':').'" />',
							'<input type="password" id="qa_password" name="password" placeholder="'.trim(qa_lang_html('users/password_label'), ':').'" />',
							'<div id="qa_rememberbox"><input type="checkbox" name="remember" id="qa_rememberme" value="1"/>',
							'<label for="qa_rememberme" id="qa_remember">'.qa_lang_html('users/remember').'</label></div>',
							'<input type="hidden" name="code" value="'.qa_html(qa_get_form_security_code('login')).'"/>',
							'<input type="submit" value="'.$login['label'].'" id="qa_login" name="dologin" />',
						'</form>');
				
			}
		
		function body()
		{
			if (!qa_is_logged_in()) {
				
				$this->output('<body');
				$this->body_tags();
				$this->output('>');
				$this->body_script();
				$this->body_content();
				$this->body_footer();
				$this->body_hidden();				
				$this->output('</body>');
			}
			else {
				$this->output('<body');
			$this->body_tags();
			$this->output('>');
			
			$this->body_script();
			$this->body_header();
			$this->body_content();
			$this->body_footer();
			$this->body_hidden();
				
			$this->output('</body>');
			}
			
		}
		
	}
?>
