<?php
/**
 * @package LinkTracker
 * @author Alexander Bachmann
 * @copyright (C) 2013 - Alexander Bachmann
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// ensure a valid entry point
defined('_JEXEC') or die('Restricted Access');


// Include dependencies
jimport('joomla.application.component.controller');

$controller = JController::getInstance('LinkTracker');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
