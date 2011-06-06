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
		$subscriberField->setAddTitle(_t('DEF_NEWSLETTER.ADDNEWSLETTERSUBSCRIBER', 'Newsletter Subscriber'));

		$f->addFieldToTab('Root.Content.Subscribers', $subscriberField);
		$f->fieldByName('Root.Content.Subscribers')->setTitle(_t('DEF_NEWSLETTER.NEWSLETTERSUBSCRIBERS', 'Newsletter Subscribers'));
	
		return $f;
	}
}

class DefNewsletterSubscriberList_Controller Extends Page_Controller {
}