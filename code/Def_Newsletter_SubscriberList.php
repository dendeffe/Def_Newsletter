<?php
class Def_Newsletter_SubscriberList Extends Page {
	static $many_many = array(
		'Def_Newsletter_Subscribers' => 'Def_Newsletter_Subscriber'
	);
	
	static $belongs_many_many = array(
        'Def_Newsletters' => 'Def_Newsletter'
	);
	
	public function getCMSFields() {
		$f = parent::getCMSFields();
	
		// Add the subscriber management
		$subscriberField = new ManyManyDataObjectManager(
			$this,
			'Def_Newsletter_Subscribers',
			'Def_Newsletter_Subscriber',
			array('Email' => 'Email'), 'getCMSFields_forPopup'
		);
		$subscriberField->setAddTitle('Leden');

		$f->addFieldToTab('Root.Content.Leden', $subscriberField);
	
		return $f;
	}
}