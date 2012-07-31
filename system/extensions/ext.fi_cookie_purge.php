<?php

/**
 * FI Cookie Purge
 *
 * This extension destroys ExpressionEngine cookies unless the user has indicated their acceptance to receive them
 * 
 * Created to help ExpressionEngine sites to comply with the The Privacy and Electronic Communications (EC Directive) Regulations 2003
 * 
 * This file must be placed in the /system/extensions/ folder of your ExpressionEngine installation.
 *
 * @package   FiCookiePurge
 * @author    Simon Jones, Fountain Internet Marketing
 * @link      http://www.fountaininternet.co.uk/
 * @copyright Simon Jones
 * @license   {@link http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported} All source code commenting and attribution must not be removed. This is a condition of the attribution clause of the licence. You should have received a copy of the licence along with this work. If not, see {@link http://creativecommons.org/licenses/by-sa/3.0/}.
 */

if ( ! defined('EXT')) exit('Invalid file request');

class Fi_cookie_purge
{
	var $settings		= array();
 	var $classname		= 'Fi_cookie_purge';
	var $name			= 'FI Cookie Purge';
	var $version		= '1.0.0';
	var $description	= 'Destroys ExpressionEngine cookies unless the user has indicated their acceptance to receive them. It helps ExpressionEngine sites to comply with the The Privacy and Electronic Communications (EC Directive) Regulations 2003';
	var $settings_exist	= 'y';
	var $docs_url		= 'https://github.com/FountainInternet/fi.cookie_purge.ee_addon/README.md';
	
	/**
	 * Extension constructor
	 *
	 * @param array   $settings
	 * @since version 1.0.0
	 */
	function Fi_cookie_purge($settings = '')
	{
		$this->settings = $this->_get_site_settings($settings);
	}
	
	/**
	 * Destroy the user's session cookies
	 *
	 * @param string   $html
	 * @since version 1.0.0
	 */
	function destroy_session($html)
	{
		if (REQ == 'PAGE')
		{
			global $IN, $FNS, $PREFS;
			$accept_cookies = FALSE;
			
			$accept_cookie_names = preg_split("/(?:\r\n|\r|\n)/", $this->settings['accept_cookie_names']);
			foreach ($accept_cookie_names as $accept_cookie_name)
			{
				$accept_cookie = $_COOKIE[$accept_cookie_name];
				if ( ! empty($accept_cookie)) $accept_cookies = TRUE;
			}
			
			// User hasn't consented to receive cookies
			if (!$accept_cookies) {
				// Get the cookie names
				$cookie_names = preg_split("/(?:\r\n|\r|\n)/", $this->settings['cookie_names']);
				// Get the cookie name prefix
				$prefix = ( ! $PREFS->ini('cookie_prefix')) ? 'exp_' : $PREFS->ini('cookie_prefix') . '_';
				$prefix_length = strlen($prefix);
				
				// Loop through the cookies and expire them
				foreach ($cookie_names as $cookie_name)
				{
					$FNS->set_cookie(substr($cookie_name, $prefix_length), '');
				}
			}
		}

		return $html;
    }
    
    /**
	 * Activate extension
	 *
	 * @since version 1.0.0
	 */
    function activate_extension()
	{
    	global $DB, $PREFS, $LANG;
    	
    	// Get settings
		$settings = $this->_get_all_settings();
		
		// Delete old hooks
		$DB->query("DELETE FROM exp_extensions
		            WHERE class = '" . __CLASS__ . "'");
    	
		$DB->query($DB->insert_string('exp_extensions',	
				array(
					'extension_id'	=> '',
					'class'			=> __CLASS__,
					'method'		=> 'destroy_session',
					'hook'			=> 'sessions_end',
					'priority'		=> 10,
					'version'		=> $this->version,
					'enabled'		=> 'y'
					)
			)
		);
	}
    
    /**
	 * Update extension
	 *
	 * @param string   $current   Previous installed version of the extension
	 * @since version 1.0.0
	 */
    function update_extension($current='')
    {
    	global $DB;

    	if ($current == '' OR $current == $this->version)
    	{
    		return FALSE;
    	}
    	
    	$DB->query("UPDATE exp_extensions 
    				SET version = '" . $DB->escape_str($this->version) . "' 
    				WHERE class = '" . __CLASS__ . "'");
    }
    
	/**
	 * Disable extension
	 *
	 * @since version 1.0.0
	 */
	function disable_extension()
	{
		global $DB;
		
		$DB->query("DELETE FROM exp_extensions WHERE class = '" . __CLASS__ . "'");
	}
	
	/**
	 * Get all settings
	 *
	 * @return array   All extension settings
	 * @since  version 1.0.0
	 */
	function _get_all_settings()
	{
		global $DB;
		
		$query = $DB->query("SELECT settings
		                     FROM exp_extensions
		                     WHERE class = '" . __CLASS__ . "'
		                       AND settings != ''
		                     LIMIT 1");
		
		return $query->num_rows
			? unserialize($query->row['settings'])
			: array();
	}

	/**
	 * Get site settings
	 *
	 * @param  array   $settings   Current extension settings (not site-specific)
	 * @return array               Site-specific extension settings
	 * @since  version 1.0.0
	 */
	function _get_site_settings($settings = array())
	{
		global $PREFS;
		
		$site_settings = $this->_get_default_settings();
		
		$site_id = $PREFS->ini('site_id');
		if (isset($settings[$site_id]))
		{
			$site_settings = array_merge($site_settings, $settings[$site_id]);
		}

		return $site_settings;
	}
	
	/**
	 * Get default settings
	 * 
	 * @return array   Default settings for site
	 * @since 1.0.0
	 */
	function _get_default_settings()
	{
		$settings = array(
			'cookie_names' => "exp_last_visit\r\nexp_last_activity\r\nexp_tracker",
			'accept_cookie_names' => "exp_accept_cookies"
		);

		return $settings;
	}
	
	/**
	 * Settings form
	 *
	 * Construct the custom settings form.
	 *
	 * @param  array   $current   Current extension settings (not site-specific)
	 * @see    http://expressionengine.com/docs/development/extensions.html#settings
	 * @since  version 1.0.0
	 */
	function settings_form($current)
	{
		global $DSP, $LANG, $IN;
	    
	    $current = $this->_get_site_settings($current);

		$DSP->crumbline = TRUE;

		$DSP->title  = $LANG->line('extension_settings');
		$DSP->crumb  = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'area=utilities', $LANG->line('utilities')).
		$DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=extensions_manager', $LANG->line('extensions_manager')));

		$DSP->crumb .= $DSP->crumb_item($LANG->line('fi_cookie_purge_name') . " {$this->version}");

		$DSP->right_crumb($LANG->line('disable_extension'), BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=toggle_extension_confirm'.AMP.'which=disable'.AMP.'name='.$IN->GBL('name'));

		$DSP->body = '';
		
		// Main title
		$DSP->body .= $DSP->heading($LANG->line('fi_cookie_purge_name') . " <small>v{$this->version}</small>");

		// Open the form
		$DSP->body .= $DSP->form_open(
								array('action' => 'C=admin'.AMP.'M=utilities'.AMP.'P=save_extension_settings'),
								array('name' => strtolower(get_class($this)))
		);
		
		// Open the table
		$DSP->body .= $DSP->table_open(
			array(
				'class' 	=> 'tableBorder',
				'border' 	=> '0',
				'style' 	=> 'width : 100%; margin-top : 1em;',
				)
			);
			
		$DSP->body .= $DSP->tr();
		$DSP->body .= $DSP->td('tableHeading', '', '2');
		$DSP->body .= $LANG->line('fi_extension_settings');
		$DSP->body .= $DSP->td_c();
		$DSP->body .= $DSP->tr_c();
			
		$DSP->body .= $DSP->tr();
        $DSP->body .= $DSP->td('tableCellOne', '20%');
        $DSP->body .= $LANG->line('fi_cookie_names');
        $DSP->body .= $DSP->td_c();
        $DSP->body .= $DSP->td('tableCellOne', '80%');
		$DSP->body .= $DSP->input_textarea('cookie_names', (isset($current['cookie_names'])) ? $current['cookie_names'] : '' , 5, 'textarea', '50%');
        $DSP->body .= $DSP->td_c();
        $DSP->body .= $DSP->tr_c();
			
		$DSP->body .= $DSP->tr();
        $DSP->body .= $DSP->td('tableCellTwo', '20%');
        $DSP->body .= $LANG->line('fi_accept_cookie_names');
        $DSP->body .= $DSP->td_c();
        $DSP->body .= $DSP->td('tableCellTwo', '80%');
		$DSP->body .= $DSP->input_textarea('accept_cookie_names', (isset($current['accept_cookie_names'])) ? $current['accept_cookie_names'] : '', 3, 'textarea', '50%');
        $DSP->body .= $DSP->td_c();
        $DSP->body .= $DSP->tr_c();
		
		// Close the table
        $DSP->body .= $DSP->table_c();
        
		// Submit button
		$DSP->body .= $DSP->qdiv('itemWrapperTop', $DSP->input_submit());
        
        // Close the form
        $DSP->body .= $DSP->form_c();
	}
	
	/**
	 * Save settings
	 *
	 * @since version 1.0.0
	 */
	function save_settings()
	{
		global $DB, $PREFS;
		
		$settings = $this->_get_all_settings();
		$current = $this->_get_site_settings($settings);

		// add the posted values to the settings
		$settings[$PREFS->ini('site_id')] = $this->settings = $_POST;

		// update the settings
		$query = $DB->query($sql = "UPDATE exp_extensions SET settings = '" . addslashes(serialize($settings)) . "' WHERE class = '" . $this->classname . "'");
	}

}
// END CLASS Fi_cookie_purge

/* End of file ext.fi_cookie_purge.php */
/* Location: ./system/extensions/ext.fi_cookie_purge.php */