<?php

class Def_Newsletter_Subscriber_Unapproved extends DataObject {
	static $db = array (
		'Email' => 'Text',
		'List' => 'Int',
		'Password' => 'Text'
	);

	public function getCMSFields_forPopup()
		{
		return new FieldSet(
			new TextField('Email')
		);
	}
}