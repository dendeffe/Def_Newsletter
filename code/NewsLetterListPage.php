<?php

//class NewsLetterListPage Extends Page {
//	static $singular_name = "Nieuwsbrief adreslijst";
//	
//	static $many_many = array(
//		'SSNewsletterSubscribers' => 'SSNewsletterSubscriber'
//	);
//	
//	static $belongs_many_many = array(
//		'NewsLetterPages' => 'NewsLetterPage'
//	);
//	
//	public function getCMSFields() {
//		$f = parent::getCMSFields();
//		$f->removeByName("Important");
//		$f->removeByName("Quotes");
//		
//		// Add the subscriber management
//		$subscriberField = new ManyManyDataObjectManager(
//		$this,
//		'SSNewsletterSubscribers',
//		'SSNewsletterSubscriber',
//		array(
//		       'Email' => 'Email'
//		), 'getCMSFields_forPopup');
//		$subscriberField->setAddTitle('Leden');
//
//		$f->addFieldToTab('Root.Content.Leden', $subscriberField);
//		
//		return $f;
//	}
//}
//
//class NewsLetterListPage_Controller Extends Page_Controller {
//	
//}