<?php

class DefNewsletterSubscriber extends DataObject {
	
	static $db = array (
		'Email' => 'Text'
	);
	
	static $belongs_many_many = array(
		'DefNewsletterSubscriberLists' => 'DefNewsletterSubscriberList'
	);
	
	
	public function getCMSFields_forPopup()
		{
		return new FieldSet(
			new TextField('Email')
		);
	}
	
}