<?php

namespace Combodo\iTop\Portal\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MetaModel;
use Combodo\iTop\Portal\Helper\SecurityHelper;


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
		$sObjectClass = $oApp['request_manipulator']->ReadParam('sObjectClass', '');
		$sObjectId = $oApp['request_manipulator']->ReadParam('sObjectId', '');

		if (!($this->CheckSecurity($oApp, $sObjectClass, $sObjectId))) {
			return parent::DocumentAction($oRequest, $oApp, $sOperation);
		}

		$bIsGravatarAtt = false;
		if ($sObjectClass === 'Person')
		{
			$sObjectField = $oApp['request_manipulator']->ReadParam('sObjectField', '');
			/** @var Person $oPerson */
			$oPerson = MetaModel::GetObject($sObjectClass, $sObjectId, true, true);
			$bIsGravatarAtt = $oPerson->IsGravatarPictureAtt($sObjectField);
		}

		if (!$bIsGravatarAtt) {
			return parent::DocumentAction($oRequest, $oApp, $sOperation);
		}

		/** @noinspection PhpUndefinedVariableInspection */
		/** @var \ormGravatarImage $oPicVal */
		$oPicVal = $oPerson->Get($sObjectField);
		$sGravatarUrl = $oPicVal->GetDisplayURL($sObjectClass, $sObjectId, $sObjectField);
		$oResponse = new RedirectResponse($sGravatarUrl);

		return $oResponse;
	}

	/**
	 * **Warning:** This is a copy-paste from original ObjectController !
	 *
	 * @param \Silex\Application $oApp
	 * @param string $sObjectClass
	 * @param string $sObjectId
	 *
	 * @return bool false if forbidden
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	protected function CheckSecurity($oApp, $sObjectClass, $sObjectId)
	{
		// When reaching to an Attachment, we have to check security on its host object instead of the Attachment itself
		if($sObjectClass === 'Attachment')
		{
			$oAttachment = MetaModel::GetObject($sObjectClass, $sObjectId, true, true);
			$sHostClass = $oAttachment->Get('item_class');
			$sHostId = $oAttachment->Get('item_id');
		}
		else
		{
			$sHostClass = $sObjectClass;
			$sHostId = $sObjectId;
		}

		// Checking security layers
		// Note: Checking if host object already exists as we can try to download document from an object that is being created
		if (($sHostId > 0) && !SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sHostClass, $sHostId))
		{
			return false;
		}

		return true;
	}
}