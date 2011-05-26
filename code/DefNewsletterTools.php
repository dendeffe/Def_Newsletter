<?php

class DefNewsletterTools extends ContentController {
	public static $UnsubscribeAddress = '';
	public static $Lists = '';
	
	static $allowed_actions = array(
		'unsubscribe',
		'subscribe',
		'unsubscribeForm',
		'subscribeForm',
		'unsubscribed',
		'subscribed',
		'confirmSubscription',
		'subscriptionConfirmed',
		'subscriptionfail'
	);
	
	function init() {
		parent::init();
	}
	
	public function subscribe()
	{
		self::$Lists = $this->SubscribedLists(FALSE);
		$customFields = array(
			"SubscribeForm" => $this->subscribeForm(self::$Lists)
		);
		return $this->renderWith('SubscribePage', $customFields);
	}
	
	public function unsubscribe()
	{
			if(strpos($this->getAddress(), '/') && strpos($this->getAddress(), '@'))
			{
				self::$UnsubscribeAddress = $this->urlParamMailAddress();
				self::$Lists = $this->SubscribedLists(TRUE);
			}

			if(self::$UnsubscribeAddress)
			{
				$customFields = array(
					"UnsubscribeForm" => $this->unsubscribeForm(),
					"UnsubscribeAddress" => self::$UnsubscribeAddress
				);
				return $this->renderWith('UnsubscribeForm', $customFields);
			} else {
				Director::redirect(Director::absoluteBaseURL());
			}
	}
	
	protected function urlParamMailAddress()
	{
			//$myURLParams = Director::urlParams();
			$myURL = $_SERVER['REQUEST_URI'];
			$myMailAddress = explode("/", $myURL);
			$myMailAddress = $myMailAddress[3];
			$myMailAddress = explode("?", $myMailAddress);
			$myMailAddress = $myMailAddress[0];
			$myMailAddress = Convert::raw2sql($myMailAddress);
			if(Email::validEmailAddress($myMailAddress))
			{
				return $myMailAddress;
			} else {
				return false;
			}
	}
	
	protected function getAddress()
	 {
	    return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 }
	
	/* 
	Check which lists are available
	Optionally check if the email address is already susbscribed to certain lists
	 */
	
	public function SubscribedLists($isSubscribedTo = TRUE, $emailTocheck = '')
	{
		$mySubscribers = '';
		$mySubscribedLists = array();
		
		// Get the subscriber e-mail
		if($isSubscribedTo)
		{
			$myEmail = self::$UnsubscribeAddress;
		} else {
			$myEmail = $emailTocheck;
		}
		
		// Get Subscriber objects for all emails you want to check
		if($isSubscribedTo)
		{
			$mySubscribers = DataObject::get('DefNewsletterSubscriber', "Email='$myEmail'");
		}
		
	
		// If you want to get lists already subscribed to
		if($mySubscribers)
		{
			$mySubscribers = $mySubscribers->getIterator();
			foreach($mySubscribers as $subscriber)
			{
				// decide if you want to search for subscribed or unsubscibed to
				if($isSubscribedTo)
				{
					$whereClause = "DefNewsletterSubscriberID = $subscriber->ID";
				} else {
					$whereClause = "DefNewsletterSubscriberID != $subscriber->ID";
				}
				
				// get all connections in the DB
				$myQuery = new SQLQuery(
					$select = "*",
					$from = array("DefNewsletterSubscriberList_DefNewsletterSubscribers"),
					// return either subscribedTo or not subscribedTo lists
					$where = $whereClause,
					$orderby = "",
					$groupby = "",
					$having = "",
					$limit = ""
				);
				$result = $myQuery->execute();
				// build the array for subscribed lists
				foreach($result as $row) {
					$subscriberID = $row['DefNewsletterSubscriberID'];
					$newsletterList = DataObject::get_by_id("Page", $row['DefNewsletterSubscriberListID']);
					if($newsletterList)
					{
						$mySubscribedLists[$newsletterList->ID . '_' . $subscriberID] = $newsletterList->Title;
					}
				}
			}
			return $mySubscribedLists;
		} else if(!$isSubscribedTo) {
			$allLists = DataObject::get('DefNewsletterSubscriberList');
			foreach($allLists as $row) {
				$mySubscribedLists[$row->ID . '_0'] = $row->Title;
			}
			return $mySubscribedLists;
		} else {
			return false;
		}
	}
	
	public function subscribeForm($lists)
	{
			$validator = new RequiredFields(
				'subscribeTo',
				'Email'
		      );

			$SubscribedList = $lists;
			$subscribeForm = new Form(
				$this,
				"subscribeForm",
				$mainFieldset = new FieldSet(
					$emailField = new EmailField('Email', 'Email*:'),
					$selectionField = new CheckBoxSetField('subscribeTo', 'Choose which newsletters you want to subscribe to*:', $SubscribedList)
				),
				new FieldSet(
					new FormAction('subscribeAction', 'Subscribe')
				), $validator
			);
			$selectionField->setCustomValidationMessage('Choose at least one newsletter to subscribe to.');
			return $subscribeForm;
	}
	
	protected function unsubscribeForm()
	{
			$validator = new RequiredFields(
				'UnsubScribeFrom'
		      );
	
			$SubscribedList = self::$Lists;
			$unsubscribeForm = new Form(
				$this,
				"unsubscribeForm",
				$mainFieldset = new FieldSet(
					$selectionField = new CheckBoxSetField('UnsubScribeFrom', 'You are subscribed to these newsletters. Choose from which ones you want to unsubscribe:*', $SubscribedList)
				),
				new FieldSet(
					new FormAction('unsubscribeAction', 'Unsubscribe')
				), $validator
			);
			$selectionField->setCustomValidationMessage('Choose at least one newsletter to unsubscribe from.');
			return $unsubscribeForm;
	}
	
	public function unsubscribeAction($data)
	{
		if(isset($data['UnsubScribeFrom']))
		{
			//echo 'must delete must delete';
			foreach($data['UnsubScribeFrom'] as $newsletterConnection)
			{
				$newsletterConnection = explode("_", $newsletterConnection);
				$newsletterID = $newsletterConnection[0];
				$subscriberID = $newsletterConnection[1];
				
				$myQuery = new SQLQuery(
					$select = "*",
					$from = array("DefNewsletterSubscriberList_DefNewsletterSubscribers"),
					$where = "DefNewsletterSubscriberID = $subscriberID AND DefNewsletterSubscriberListID = $newsletterID"
				);
				$myQuery->delete = true;
				$result = $myQuery->execute();
			}
		}
		Director::redirect(Director::absoluteBaseURL() . "newslettertools/unsubscribed");
	}
	
	
	public function subscribeAction($data)
	{

		if(isset($data['subscribeTo']))
		{
			
			foreach ($data['subscribeTo'] as $list)
			{	
				$myList = explode('_', $list);
				$myList = $myList[0];
				$myListTitle = DataObject::get_by_id('DefNewsletterSubscriberList', $myList);
				$myListTitle = $myListTitle->Title;
				$myEmail = $data['Email'];
				$myPassword = $this->createRandomPassword();
				
				$myNewSubscriber = new DefNewsletterSubscriberUnapproved;
				$myNewSubscriber->Email = $myEmail;
				$myNewSubscriber->List = $myList;
				$myNewSubscriber->Password = $myPassword;
				$myNewSubscriber->write();
				$mySubscriberID = $myNewSubscriber->ID;
				
				$succes = $this->SendConfirmationMail($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID);
				if($succes)
				{
					Director::redirect(Director::absoluteBaseURL() . "newslettertools/subscribed");
				} else {
					Director::redirect(Director::absoluteBaseURL() . "newslettertools/subscriptionfail");
				}
			}
			
		}
		
	}
	
	public function confirmSubscription()
	{
		$urlConfirmCode = $this->urlConfirmCode();

		// see if the record exists
		if($urlConfirmCode[0] && is_numeric($urlConfirmCode[0]))
		{

			$myUnapprovedSubscriber = DataObject::get_by_id('DefNewsletterSubscriberUnapproved', $urlConfirmCode[0]);

			// check if the passwords match
			if($myUnapprovedSubscriber->Password == $urlConfirmCode[1])
			{
				$newsLetterList = DataObject::get_by_id('DefNewsletterSubscriberList', $myUnapprovedSubscriber->List);
				// Create the subscriber
				$myApprovedSubscriber = new DefNewsletterSubscriber();
				$myApprovedSubscriber->Email = $myUnapprovedSubscriber->Email;
				$myApprovedSubscriber->write();
				// Add the subcriber to the list
				$newsLetterList->DefNewsletterSubscribers()->add($myApprovedSubscriber);
				// Change the password so there can not be any duplicate subscriptions
				$myUnapprovedSubscriber->Password = 0;
				$myUnapprovedSubscriber->write();
				Director::redirect(Director::absoluteBaseURL() . "newslettertools/subscriptionConfirmed");
			} else {
				Director::redirect(Director::absoluteBaseURL());
			}
		} else {
			Director::redirect(Director::absoluteBaseURL());
		}
	}
	
	protected function urlConfirmCode()
	{
			$myURL = $_SERVER['REQUEST_URI'];
			$myConfirmCode = explode("/", $myURL);
			$myConfirmCode = $myConfirmCode[3];
			$myConfirmCode = explode("_", $myConfirmCode);
			for($x = 0; $x < count($myConfirmCode); $x++)
			{
				$myConfirmCode[$x] = Convert::raw2sql($myConfirmCode[$x]);
			}
			return $myConfirmCode;
	}
	
	public function createRandomPassword() { 

	    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
	    srand((double)microtime()*1000000); 
	    $i = 0; 
	    $pass = '' ; 

	    while ($i <= 7) { 
	        $num = rand() % 33; 
	        $tmp = substr($chars, $num, 1); 
	        $pass = $pass . $tmp; 
	        $i++; 
	    } 

	    return $pass; 

	}
	
	function SendConfirmationMail($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID) {
		$recipients = array($myEmail);

		$htmlContent = "<h3>Confirm your subscription to this newsletter: " . $myListTitle . "</h3>
		<p>Click the following link to confirm your subscription: <a href=\"" . Director::absoluteBaseURL() . "newslettertools/confirmSubscription/" . $mySubscriberID . "_" . $myPassword  . "\">Confirm your subscription</a></p>";
		$plainTextContent = "Confirm your subscription to the newsletter: " . $myListTitle . "\n\nOpen this link in your browser: " . Director::absoluteBaseURL() . "newslettertools/confirmSubscription/" . $mySubscriberID . "_" . $myPassword;
		$myMail = Def_MailTools::BuildMail($htmlContent, $plainTextContent, 'MailHeader', 'NewsletterMailFooter');

   		// send message 
   		if (Def_MailTools::send_mail_sendgrid($recipients, 'Confirm your subscription', $myMail, '', false))
   		{
   		  return true;
   		}
   		else
   		{
   		  return false;
   		}
   }

	public function unsubscribed()
	{
		return $this->renderWith('Unsubscribed');
	}

	public function subscribed()
	{
		return $this->renderWith('Subscribed');
	}

	public function subscriptionConfirmed()
	{
		return $this->renderWith('SubscriptionConfirmed');
	}

	public function subscriptionfail()
	{
		return $this->renderWith('SubscriptionFail');
	}

}