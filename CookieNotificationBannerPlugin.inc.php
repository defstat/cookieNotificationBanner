<?php

/**
 * @file plugins/generic/cookieNotificationBanner/CookieNotificationBannerPlugin.inc.php
 *
 * Copyright (c) 2017 National Documentation Centre
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class JatsXmlEditorPlugin
 * @ingroup plugins_generic_cookieNotificationBanner
 *
 * @brief CookieNotificationBannerPugin
 */


import('classes.plugins.GenericPlugin');

class CookieNotificationBannerPlugin extends GenericPlugin {

	/**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();

		return parent::getManagementVerbs($verbs);
	}

	/**
	 * Get the handler path for this plugin.
	 */
	function getHandlerPath() {
		return $this->getPluginPath() . '/pages/';
	}

	/**
	 * Get the handler path for this plugin.
	 */
	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';

	}

	/**
	 * Perform management functions
	 */
	function manage($verb, $args, &$message, &$messageParams) {

		return parent::manage($verb, $args, $message, $messageParams);

	}

	/**
	 * Display book for review metadata link in submission summary page.
	 */
	function displayCookieNotification($hookName, $params) {
		$smarty =& $params[1];
		$output =& $params[2];
		$submission =& $smarty->get_template_vars("submission");

		$templateMgr = TemplateManager::getManager();
		// $output = '<p><a href="' .$templateMgr->smartyUrl(array('page'=>'editor', 'op'=>'editJatsXML', 'path'=>$submission->getId(), 'callbackUrl'=>'edit', 'articleId'=>$submission->getId()), $smarty) . '" class="action">' . $templateMgr->smartyTranslate(array('key'=>'plugins.generic.jatsXmlEditor.editJatsFileLink'), $smarty) . '</a></p>';
		$output = '<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
					<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
					<script>
					window.addEventListener("load", function(){
					window.cookieconsent.initialise({
					  "palette": {
						"popup": {
						  "background": "#edeff5",
						  "text": "#838391"
						},
						"button": {
						  "background": "#4b81e8"
						}
					  },
					  "content": {
						"message": "' . $templateMgr->smartyTranslate(array('key'=>'plugins.generic.cookieNotificationBanner.message'), $smarty) . '"
					  }
					})});
					</script>';

		return false;
	}


	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True iff plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		$this->addLocaleData();

		HookRegistry::register('Templates::Common::RightSidebar', array($this, 'displayCookieNotification'));

		return $success;
	}

	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'CookieNotificationBanner';
	}

	function getDisplayName() {
		return __('plugins.generic.cookieNotificationBanner.displayName');
	}

	function getDescription() {
		return __('plugins.generic.cookieNotificationBanner.description');
	}

	/**
	 * Extend the {url ...} for smarty to support this plugin.
	 */
	function smartyPluginUrl($params, &$smarty) {
		$path = array('plugin',$this->getName());
		if (is_array($params['path'])) {
			$params['path'] = array_merge($path, $params['path']);
		} elseif (!empty($params['path'])) {
			$params['path'] = array_merge($path, array($params['path']));
		} else {
			$params['path'] = $path;
		}

		if (!empty($params['id'])) {
			$params['path'] = array_merge($params['path'], array($params['id']));
			unset($params['id']);
		}
		return $smarty->smartyUrl($params, $smarty);
	}

}

?>
