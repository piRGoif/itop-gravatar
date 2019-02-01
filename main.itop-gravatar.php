<?php
@include_once('../../approot.inc.php');
@include_once('../approot.inc.php');
require_once(APPROOT.'application/utils.inc.php');


$sModulePath = utils::GetCurrentModuleDir(0);
require_once(MODULESROOT.$sModulePath.'/lib/gravatarlib/Gravatar.php');


class ormGravatarImage extends ormDocument
{
	private $sEmailValue;
	private $sDefaultImageUrl;
	private $iMaxSize;

	/**
	 * We can have different parameters than in parent class... but this breaks substitution principle !
	 * Ok for our use case, but kids, do not do this at home ;)
	 *
	 * @see http://www.php.net/oop5.decon
	 *
	 * @param \ormDocument $value
	 * @param string $sEmailValue
	 * @param string $sDefaultImageUrl
	 * @param int $iMaxSize
	 */
	public function __construct(ormDocument $value, $sEmailValue, $sDefaultImageUrl, $iMaxSize)
	{
		$data = $value->GetData();
		$sMimeType = $value->GetMimeType();
		$sFileName = $value->GetFileName();

		parent::__construct($data, $sMimeType, $sFileName);

		$this->sEmailValue = $sEmailValue;
		$this->sDefaultImageUrl = $sDefaultImageUrl;
		$this->iMaxSize = $iMaxSize;
	}

	/**
	 * @return bool false, as we will always return an URL
	 */
	public function IsEmpty()
	{
		return false;
	}

	private function IsEmptyvalue()
	{
		return parent::IsEmpty();
	}

	public function GetDownloadURL($sClass, $Id, $sAttCode)
	{
		$bIsEmptyValue = $this->IsEmptyValue();
		$bEmptyEmail = empty($this->sEmailValue);
		IssueLog::Info('ormGravatar: email='.$this->sEmailValue);
		if (!$bIsEmptyValue || $bEmptyEmail)
		{
			return parent::GetDownloadURL($sClass, $Id, $sAttCode);
		}

		$sGravatarUrl = $this->GetGravatarUrl($this->sEmailValue, $this->sDefaultImageUrl, $this->iMaxSize);

		return $sGravatarUrl;
	}

	/**
	 * @param string $sEmail
	 * @param string $sDefaultImageUrl won't work for localhost but ok with a valid hostname (eg
	 *     https://demo.combodo.com/simple/env-production/itop-config-mgmt/images/silhouette.png)
	 * @param int $iMaxSize
	 *
	 * @return string
	 */
	private function GetGravatarUrl($sEmail, $sDefaultImageUrl, $iMaxSize)
	{
		if (empty($sEmail))
		{
			return null;
		}

		$oGravatar = new \emberlabs\GravatarLib\Gravatar();
		$oGravatar->enableSecureImages()
			->setDefaultImage($sDefaultImageUrl)
			->setAvatarSize($iMaxSize);

		return $oGravatar->buildGravatarURL($sEmail);
	}
}
