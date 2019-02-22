<?php

namespace Combodo\iTop\Portal\Router;

class GravatarObjectRouter extends AbstractRouter
{
	static $aRoutes = array(
		array('pattern' => '/object/document/display/{sObjectClass}/{sObjectId}/{sObjectField}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\GravatarObjectController::DocumentAction',
			'bind' => 'p_object_document_display',
			'values' => array(
				'sOperation' => 'display'
			)
		),
	);
}