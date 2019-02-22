<?php

// we need to load the router and each of its dependencies :/
require_once 'gravatar-objectrouter.class.inc.php';
require_once APPROOT.'env-production/itop-portal-base/portal/src/controllers/objectcontroller.class.inc.php';
require_once 'gravatar-objectcontroller.class.inc.php';


class GravatarUIPortalExtension extends AbstractPortalUIExtension
{
	public function GetBodyHTML(\Silex\Application $oApp)
	{
		\Combodo\iTop\Portal\Router\GravatarObjectRouter::RegisterAllRoutes($oApp);

		return parent::GetBodyHTML($oApp);
	}
}