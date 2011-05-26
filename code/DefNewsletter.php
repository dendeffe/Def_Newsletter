<?php

class DefNewsletter extends Page {
	static $db = array(
        'AlreadySent' => 'Boolean'
	);
	
	static $many_many = array(
        'DefNewsletterSubscriberLists' => 'DefNewsletterSubscriberList'
	);

	
	public function getCMSFields() {
        $f = parent::getCMSFields();

        // Add the newsletter list management
		$listField = new ManyManyDataObjectManager(
			$this,
			'DefNewsletterSubscriberLists',
			'DefNewsletterSubscriberList',
			array(
			       'Title' => 'Title'
			), 'getCMSFields_forPopup'
		);
        $listField->setAddTitle('Subscriber lists');
        $f->addFieldToTab('Root.Content.SubscriberLists', $listField);
        return $f;
	}
	
}

class DefNewsletter_Controller extends Page_Controller {
	public function init() {
		parent::init();
		Requirements::clear();
		Requirements::javascript('sapphire/thirdparty/jquery/jquery-packed.js');
		Requirements::javascript('def_newsletter/javascript/def_newsletter.js');
		Requirements::css('def_newsletter/css/def_newsletter.css');
	}
	
	function TestMailForm(){
		$testmailForm = new Form(
			$this,
			"TestMailForm",
			$mainFieldset = new FieldSet(
				$howManyField = new EmailField("TestEmail", "Email")
			),
			new FieldSet(
				new FormAction('SendTestMail', 'Send')
			)
		);
		return $testmailForm;
	}
	
	function SendTestMail($data) {
		
		if($this->IsAdmin()) {
			$recipients = array($data["TestEmail"]);
			$htmlContent = $this->Content;
			$myMail = Def_MailTools::BuildMail($htmlContent, '', 'NewsletterMailHeader', 'NewsletterMailFooter');
			
		
			if (Def_MailTools::send_mail_sendgrid($recipients, '-- PREVIEW MAIL -- ' . $this->Title, $myMail))
			{
				//return true;
			}
			else
			{
				echo "Something went wrong - ";
				print_r($failures);
				exit();
			}
	
			Director::redirectBack();
		} else {
			Director::redirect(Director::baseURL());
		}
	}
	
	function SendEntireListForm(){
		$sendEntireListForm = new Form(
			$this,
			"sendEntireListForm",
			$mainFieldset = new FieldSet(
			),
			new FieldSet(
				new FormAction('SendEntireList', 'Send')
			)
		);
		return $sendEntireListForm;
	}
	
	function SendEntireList() {
		if($this->IsAdmin()) {
			$myRecipients = $this->Recipients();
		
			$htmlContent = $this->Content;
			$myMail = Def_MailTools::BuildMail($htmlContent, '', 'NewsletterMailHeader', 'NewsletterMailFooter');
			
		
			if (Def_MailTools::send_mail_sendgrid($myRecipients, $this->Title, $myMail))
			{
				//return true;
			}
			else
			{
				echo "Something went wrong - ";
				print_r($failures);
				exit();
			}
		
			$myOwnPage = DataObject::get_by_id('DefNewsletter', $this->ID);
			$myOwnPage->AlreadySent = 1;
			$myOwnPage->write();
		
			Director::redirectBack();
		} else {
			Director::redirect(Director::baseURL());
		}
	
	}
	
	
	function IsAdmin() 
	{
		$currentUser = new Member();
		$currentUser = $currentUser->currentUser();
		if($currentUser)
		{
			return $currentUser->isAdmin();
		} else {
			return false;
		}

	}

	function RedirectNonAdmins()
	{
		if(!$this->IsAdmin()) {
	        Director::redirect(Director::baseURL());
		}
	}

	function HTMLHeader()
	{
		return $this->renderWith('DefNewsletterInterfaceHeader');
	}

	function HTMLFooter()
	{
		return $this->renderWith('DefNewsletterInterfaceFooter');
	}


	function MailContent()
	{
		$myContent = $this->Content;
		$myContent = str_replace('src="assets', 'src="' . Director::absoluteBaseURL() . 'assets', $myContent);

		$myContent = $this->renderWith('NewsletterMailHeader') . $myContent . $this->renderWith('NewsletterMailFooter');

		return $myContent;
	}

	function MailContentPreview()
	{
		$myContent = $this->MailContent();
		$myBodyStart = strpos($myContent, '<body');
		$myBodyStart = strpos($myContent, '>', $myBodyStart) + 1;

		$mypreviewContent = substr($myContent, $myBodyStart, -7);

		return $mypreviewContent;
	}

	function AlreadySent()
	{
		$myPage = Versioned::get_latest_version('DefNewsletter', $this->ID);
		if($myPage->AlreadySent == 1)
		{
			return 1;
		} else {
			return 0;
		}
	}

	function Lists()
	{
		$myLists = $this->getManyManyComponents('DefNewsletterSubscriberLists');
		$myLists = $myLists->items;
		$myLists = new DataObjectSet($myLists);
		return $myLists;
	}


	function DisplayRecipients()
	{

		$myRecipients = $this->Recipients();

		if(count($myRecipients)) {

			$myDisplayRecipients = "<ul>";

			for($x = 0; $x < count($myRecipients); $x++)

			{

				$myDisplayRecipients .= "<li>" . $myRecipients[$x] . "</li>";

			}

			$myDisplayRecipients .= "</ul>";

		} else {

			$myDisplayRecipients = "<p>Geen emailadressen</p>";

		}

		return $myDisplayRecipients;

	}

	function Recipients()
	{
		$emailsOnList = array();
		$myLists = $this->DefNewsletterSubscriberLists();
		$myLists = $myLists->items;
		// loop through mailing lists
		for($x = 0; $x < count($myLists); $x++)
		{
			$myEmails = $myLists[$x]->getManyManyComponents('DefNewsletterSubscribers');
			$myEmails = $myEmails->items;
			// loop emails
			for($y = 0; $y < count($myEmails); $y++) {
				$emailsOnList[] = $myEmails[$y]->Email;
			}
		}

		$emailsOnList = array_unique($emailsOnList);
		return $emailsOnList;
	}
}