<?php

class DefNewsletterSubscriberUnapproved extends DataObject {
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