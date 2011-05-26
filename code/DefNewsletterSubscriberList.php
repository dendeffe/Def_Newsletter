<?php
class DefNewsletterSubscriberList Extends Page {
	static $many_many = array(
		'DefNewsletterSubscribers' => 'DefNewsletterSubscriber'
	);
	
	static $belongs_many_many = array(
        'DefNewsletters' => 'DefNewsletter'
	);
	
	public function getCMSFields() {
		$f = parent::getCMSFields();
	
		// Add the subscriber management
		$subscriberField = new ManyManyDataObjectManager(
			$this,
			'DefNewsletterSubscribers',
			'DefNewsletterSubscriber',
			array('Email' => 'Email'), 'getCMSFields_forPopup'
		);
		$subscriberField->setAddTitle('Subscribers');

		$f->addFieldToTab('Root.Content.Subscribers', $subscriberField);
	
		return $f;
	}
}

class DefNewsletterSubscriberList_Controller Extends Page_Controller {
}