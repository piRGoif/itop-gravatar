<?php

// we need to load the router and each of its dependencies :/
// very ugly but no other solution for now...
require_once 'gravatar-objectrouter.class.inc.php';
require_once APPROOT.'env-production/itop-portal-base/portal/src/controllers/objectcontroller.class.inc.php';
require_once 'gravatar-objectcontroller.class.inc.php';


class GravatarUIPortalExtension extends AbstractPortalUIExtension
{
	public function GetBodyHTML(\Silex\Application $oApp)
	{
		// If we do nothing, the app will register our router... but before the default one
		// (see \Combodo\iTop\Portal\Helper\ApplicationHelper::RegisterRoutes)
		// So we need to register it again AFTER the default route in order to override it
		\Combodo\iTop\Portal\Router\GravatarObjectRouter::RegisterAllRoutes($oApp);
	}
}