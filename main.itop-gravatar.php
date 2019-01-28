<?php
@include_once('../../approot.inc.php');
@include_once('../approot.inc.php');
require_once(APPROOT.'application/utils.inc.php');


$sModulePath = utils::GetCurrentModuleDir(0);
require_once(MODULESROOT.$sModulePath.'/lib/gravatarlib/Gravatar.php');


class AttributeGravatarImage extends AttributeImage
{
	const EMAIL_ATTCODE_PARAM_KEY = 'gravatar_attcode';

	public static function FromAttrImage(AttributeImage $oImageAttDef, $sEmailAttCode)
	{
		$sCode = $oImageAttDef->GetCode();
		$aParams = $oImageAttDef->GetParams();
		$aParams[self::EMAIL_ATTCODE_PARAM_KEY] = $sEmailAttCode;
		$oGravatarAttDef = new self($sCode, $aParams);
		$oGravatarAttDef->aCSSClasses = array('attribute');

		return $oGravatarAttDef;
	}

	protected function GetAttributeImageFileUrl($value, $oHostObject)
	{
		if (!$value->IsEmpty())
		{
			return parent::GetAttributeImageFileUrl($value, $oHostObject);
		}

		$sGravatarAttCode = $this->HasParam(self::EMAIL_ATTCODE_PARAM_KEY)
			? $this->Get(self::EMAIL_ATTCODE_PARAM_KEY)
			: null;
		if ($sGravatarAttCode === null)
		{
			return null;
		}

		$sEmail = $oHostObject->Get($sGravatarAttCode);
		$sDefaultImageUrl = $this->Get('default_image');
		$sGravatarUrl = $this->GetGravatarUrl($sEmail, $sDefaultImageUrl);

		return $sGravatarUrl;
	}

	private function GetGravatarUrl($sEmail, $sDefaultImageUrl)
	{
		if (empty($sEmail))
		{
			return null;
		}

		$iMaxWidth = $this->Get('display_max_width');
		$iMaxHeight = $this->Get('display_max_height');
		$iMaxSize = max($iMaxWidth, $iMaxHeight);
		$iMaxSize = min(512, $iMaxSize);

		$oGravatar = new \emberlabs\GravatarLib\Gravatar();
		$oGravatar->enableSecureImages()
			->setDefaultImage($sDefaultImageUrl)//won't work for localhost but ok with a valid hostname (eg https://demo.combodo.com/simple/env-production/itop-config-mgmt/images/silhouette.png)
			->setAvatarSize($iMaxSize);

		return $oGravatar->buildGravatarURL($sEmail);
	}
}