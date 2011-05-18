<?php

class Def_Newsletter_Tools extends ContentController {
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
			$mySubscribers = DataObject::get('Def_Newsletter_Subscriber', "Email='$myEmail'");
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
					$whereClause = "Def_Newsletter_SubscriberID = $subscriber->ID";
				} else {
					$whereClause = "Def_Newsletter_SubscriberID != $subscriber->ID";
				}
				
				// get all connections in the DB
				$myQuery = new SQLQuery(
					$select = "*",
					$from = array("Def_Newsletter_SubscriberList_Def_Newsletter_Subscribers"),
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
					$subscriberID = $row['Def_Newsletter_SubscriberID'];
					$newsletterList = DataObject::get_by_id("Page", $row['Def_Newsletter_SubscriberListID']);
					if($newsletterList)
					{
						$mySubscribedLists[$newsletterList->ID . '_' . $subscriberID] = $newsletterList->Title;
					}
				}
			}
			return $mySubscribedLists;
		} else if(!$isSubscribedTo) {
			$allLists = DataObject::get('Def_Newsletter_SubscriberList');
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
					$emailField = new EmailField('Email', 'E-mail adres*:'),
					$selectionField = new CheckBoxSetField('subscribeTo', 'Kies op welke lijsten je wil inschrijven*:', $SubscribedList)
				),
				new FieldSet(
					new FormAction('subscribeAction', 'Inschrijven')
				), $validator
			);
			$selectionField->setCustomValidationMessage('Kies minstens 1 lijst waar je op wil inschrijven.');
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
					$selectionField = new CheckBoxSetField('UnsubScribeFrom', 'Je bent geabonneerd op onderstaande deze lijsten. Vink aan bij welke je jezelf wil uitschrijven:', $SubscribedList)
				),
				new FieldSet(
					new FormAction('unsubscribeAction', 'Uitschrijven')
				), $validator
			);
			$selectionField->setCustomValidationMessage('Kies minstens 1 lijst waar je jezelf van wil uitschrijven.');
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
					$from = array("Def_Newsletter_SubscriberList_Def_Newsletter_Subscribers"),
					$where = "Def_Newsletter_SubscriberID = $subscriberID AND Def_Newsletter_SubscriberListID = $newsletterID"
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
				$myListTitle = DataObject::get_by_id('Def_Newsletter_SubscriberList', $myList);
				$myListTitle = $myListTitle->Title;
				$myEmail = $data['Email'];
				$myPassword = $this->createRandomPassword();
				
				$myNewSubscriber = new Def_Newsletter_Subscriber_Unapproved;
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

			$myUnapprovedSubscriber = DataObject::get_by_id('Def_Newsletter_Subscriber_Unapproved', $urlConfirmCode[0]);

			// check if the passwords match
			if($myUnapprovedSubscriber->Password == $urlConfirmCode[1])
			{
				$newsLetterList = DataObject::get_by_id('Def_Newsletter_SubscriberList', $myUnapprovedSubscriber->List);
				// Create the subscriber
				$myApprovedSubscriber = new Def_Newsletter_Subscriber();
				$myApprovedSubscriber->Email = $myUnapprovedSubscriber->Email;
				$myApprovedSubscriber->write();
				// Add the subcriber to the list
				$newsLetterList->Def_Newsletter_Subscribers()->add($myApprovedSubscriber);
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
		
		$htmlContent = "<h3>Bevestig je inschrijving op de nieuwsbrief: " . $myListTitle . "</h3>
		<p>Om je inschrijving te bevestigen klik je op deze link: <a href=\"" . Director::absoluteBaseURL() . "newslettertools/confirmSubscription/" . $mySubscriberID . "_" . $myPassword  . "\">Bevestig inschrijving</a></p>";
		$plainTextContent = "Bevestig je inschrijving op de nieuwsbrief: " . $myListTitle . "\n\nOpen deze link in je browser: " . Director::absoluteBaseURL() . "newslettertools/confirmSubscription/" . $mySubscriberID . "_" . $myPassword;
		$myMail = Def_MailTools::BuildMail($htmlContent, $plainTextContent, 'MailHeader', 'NewsletterMailFooter');

   		// send message 
   		if (Def_MailTools::send_mail_sendgrid($recipients, 'Bevestig je inschrijving op de mailinglijst', $myMail, 'Subscribe to mailinglist', false))
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