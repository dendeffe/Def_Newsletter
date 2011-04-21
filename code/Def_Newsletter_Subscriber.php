<?php

class Def_Newsletter_Subscriber extends DataObject {
	
	static $db = array (
		'Email' => 'Text'
	);
	
	static $belongs_many_many = array(
		'Def_Newsletter_SubscriberLists' => 'Def_Newsletter_SubscriberList'
	);
	
	
	public function getCMSFields_forPopup()
		{
		return new FieldSet(
			new TextField('Email')
		);
	}
	
}