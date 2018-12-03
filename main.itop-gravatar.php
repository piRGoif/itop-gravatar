<?php
@include_once('../../approot.inc.php');
@include_once('../approot.inc.php');
require_once(APPROOT.'application/utils.inc.php');


class PersonCustomField implements iApplicationUIExtension
{
	/**
	 * @inheritdoc
	 */
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false)
	{
		$oPage->add('PersonCUSTOM-prop');
		//FIXME should add JS here !
	}

	/**
	 * @inheritdoc
	 */
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false)
	{
		// nothing
		IssueLog::Info('Gravatar dispRel');
	}

	/**
	 * @inheritdoc
	 */
	public function OnFormSubmit($oObject, $sFormPrefix = '')
	{
		// nothing
	}

	/**
	 * @inheritdoc
	 */
	public function OnFormCancel($sTempId)
	{
		// nothing
	}

	/**
	 * @inheritdoc
	 */
	public function EnumUsedAttributes($oObject)
	{
		// nothing
	}

	/**
	 * @inheritdoc
	 */
	public function GetIcon($oObject)
	{
		// nothing
	}

	/**
	 * @inheritdoc
	 */
	public function GetHilightClass($oObject)
	{
		// nothing
		return HILIGHT_CLASS_NONE;
	}

	/**
	 * @inheritdoc
	 */
	public function EnumAllowedActions(DBObjectSet $oSet)
	{
		// nothing
		return array();
	}
}


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