<?php
@include_once('../../approot.inc.php');


require_once(MODULESROOT.'/itop-gravatar/lib/gravatarlib/Gravatar.php');


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
		$sMimeType = 'image/png'; // used for form modifications, see #GetData
		$sFileName = $value->GetFileName();

		parent::__construct($data, $sMimeType, $sFileName);

		$this->sEmailValue = $sEmailValue;
		$this->sDefaultImageUrl = $sDefaultImageUrl;
		$this->iMaxSize = $iMaxSize;
	}

	/**
	 * @return bool false, as we will always return an URL
	 *           when modifying form we will use #GetData method
	 */
	public function IsEmpty()
	{
		return false;
	}

	public function IsEmptyValue()
	{
		return parent::IsEmpty();
	}

	public function GetDownloadURL($sClass, $Id, $sAttCode)
	{
		return $this->GetImageURL($sClass, $Id, $sAttCode);
	}

	public function GetDisplayURL($sClass, $Id, $sAttCode)
	{
		return $this->GetImageURL($sClass, $Id, $sAttCode);
	}

	protected function GetImageURL($sClass, $Id, $sAttCode)
	{
		$bIsEmptyValue = $this->IsEmptyValue();
		$bEmptyEmail = empty($this->sEmailValue);
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

	/**
	 * Used in the object modification form as preview
	 *
	 * @return mixed binary content of the gravatar icon
	 * @see \cmdbAbstractObject::GetFormElementForField
	 */
	public function GetData()
	{
		$fGravatarIcon = MODULESROOT.'/itop-gravatar/lib/gravatar-black-256-icon.png';
		$GravatarIconBinary = file_get_contents($fGravatarIcon);
		return $GravatarIconBinary;
	}
}
