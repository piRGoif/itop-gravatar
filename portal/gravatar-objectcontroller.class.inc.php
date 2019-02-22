<?php

namespace Combodo\iTop\Portal\Controller;


use MetaModel;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class GravatarObjectController extends ObjectController
{
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param \Silex\Application $oApp
	 * @param null $sOperation
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DocumentAction(Request $oRequest, Application $oApp, $sOperation = null)
	{
		// As we have a security check in the parent that can't be called directly, we're delegating first
		$oDefaultResponse = parent::DocumentAction($oRequest, $oApp, $sOperation);

		if (!$oDefaultResponse instanceof Response) {
			return $oDefaultResponse;
		}

		$sObjectClass = $oApp['request_manipulator']->ReadParam('sObjectClass', '');
		$sObjectId = $oApp['request_manipulator']->ReadParam('sObjectId', '');
		$bIsGravatarAtt = false;
		if ($sObjectClass === 'Person')
		{
			$sObjectField = $oApp['request_manipulator']->ReadParam('sObjectField', '');
			/** @var Person $oPerson */
			$oPerson = MetaModel::GetObject($sObjectClass, $sObjectId, true, true);
			$bIsGravatarAtt = $oPerson->IsGravatarPictureAtt($sObjectField);
		}

		if (!$bIsGravatarAtt) {
			return $oDefaultResponse;
		}
		/** @noinspection PhpUndefinedVariableInspection */
		/** @var \ormGravatarImage $oPicVal */
		$oPicVal = $oPerson->Get($sObjectField);
		if ($bIsGravatarAtt && (!$oPicVal->IsEmptyValue())) {
			return $oDefaultResponse;
		}

		$sGravatarUrl = $oPicVal->GetDisplayURL($sObjectClass, $sObjectId, $sObjectField);
		$oResponse = new RedirectResponse($sGravatarUrl);

		return $oResponse;
	}
}