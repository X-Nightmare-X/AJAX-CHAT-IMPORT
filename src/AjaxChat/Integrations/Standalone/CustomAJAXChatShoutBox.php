<?php
namespace AjaxChat\Integrations\Standalone;
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

class CustomAJAXChatShoutBox extends \AjaxChat\CustomAJAXChat {

	function initialize() {
		// Initialize configuration settings:
		$this->initConfig();
	}

	function getShoutBoxContent() {
		$template = new AJAXChatTemplate($this, AJAX_CHAT_PATH.'src/template/shoutbox.html');
		
		// Return parsed template content:
		return $template->getParsedContent();
	}

}
