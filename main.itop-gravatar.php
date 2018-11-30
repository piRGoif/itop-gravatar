<?php
@include_once('../../approot.inc.php');
@include_once('../approot.inc.php');
require_once(APPROOT.'application/utils.inc.php');

class CalendarViewUIextension implements iPageUIExtension
{

	/**
	 * @inheritdoc
	 */
	public function GetNorthPaneHtml(\iTopWebPage $oPage)
	{
		$oPage->add_linked_script(\utils::GetCurrentModuleUrl().'/itop-gravatar.js');
	}

	/**
	 * @inheritdoc
	 */
	public function GetSouthPaneHtml(\iTopWebPage $oPage)
	{
		// Do nothing.
	}

	/**
	 * @inheritdoc
	 */
	public function GetBannerHtml(\iTopWebPage $oPage)
	{
		// Do nothing.
	}
}