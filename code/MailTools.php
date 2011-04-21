<?php

//class MailTools extends ContentController {
//	
//	public static $UnsubscribeAddress = '';
//	public static $Lists = '';
//	
//	static $allowed_actions = array(
//		'unsubscribe',
//		'subscribe',
//		'unsubscribeForm',
//		'subscribeForm',
//		'unsubscribed',
//		'subscribed',
//		'confirmSubscription',
//		'subscriptionConfirmed',
//		'subscriptionfail'
//	);
//	
//	function init() {
//		parent::init();
//		Validator::set_javascript_validation_handler('none');
//		Requirements::javascript('sapphire/thirdparty/jquery/jquery-packed.js');
//		Requirements::javascript('mysite/javascript/customInput.jquery.js');
//		Requirements::javascript('mysite/javascript/jquery.cookie.js');
//		Requirements::javascript('mysite/javascript/main.js');
//		Requirements::css('mysite/javascript/shadowbox-3.0.3/shadowbox.css');
//		Requirements::javascript('mysite/javascript/shadowbox-3.0.3/shadowbox.js');
//	}
//	
//	function AdminMail()
//	{
//		return ADMINMAIL;
//	}
//	
//	protected function getAddress()
//	 {
//	    return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//	 }
//	
//	
//	
//	protected function urlParamMailAddress()
//	{
//			//$myURLParams = Director::urlParams();
//			$myURL = $_SERVER['REQUEST_URI'];
//			$myMailAddress = explode("/", $myURL);
//			$myMailAddress = $myMailAddress[3];
//			$myMailAddress = explode("?", $myMailAddress);
//			$myMailAddress = $myMailAddress[0];
//			$myMailAddress = Convert::raw2sql($myMailAddress);
//			if(Email::validEmailAddress($myMailAddress))
//			{
//				return $myMailAddress;
//			} else {
//				return false;
//			}
//	}
//	
//	protected function urlConfirmCode()
//	{
//			$myURL = $_SERVER['REQUEST_URI'];
//			$myConfirmCode = explode("/", $myURL);
//			$myConfirmCode = $myConfirmCode[3];
//			$myConfirmCode = explode("_", $myConfirmCode);
//			for($x = 0; $x < count($myConfirmCode); $x++)
//			{
//				$myConfirmCode[$x] = Convert::raw2sql($myConfirmCode[$x]);
//			}
//			return $myConfirmCode;
//	}
//		
//	public function unsubscribe()
//	{
//			if(strpos($this->getAddress(), '/') && strpos($this->getAddress(), '@'))
//			{
//				self::$UnsubscribeAddress = $this->urlParamMailAddress();
//				self::$Lists = $this->SubscribedLists(TRUE);
//			}
//			//echo $this->UnsubscribeAddress;
//			if(self::$UnsubscribeAddress)
//			{
//				$customFields = array(
//					"UnsubscribeForm" => $this->unsubscribeForm(),
//					"UnsubscribeAddress" => self::$UnsubscribeAddress
//				);
//				return $this->renderWith('UnsubscribeForm', $customFields);
//			} else {
//				Director::redirect(Director::absoluteBaseURL());
//			}
//	}
//	
//	public function MainClass() {
//		return Page_Controller::PreviousPageClass();
//	}
//	
//	function SiteSections() {
//		$currentSection = strtolower($this->MainClass());
//		$SiteSections = DataObject::get("Page", "ParentID = 0 AND URLSegment != '$currentSection' AND (URLSegment = 'intermediairen' OR URLSegment = 'jongeren' OR URLSegment = 'vrouwen' OR URLSegment = 'pers')", "", "", "");
//		return $SiteSections;
//	}
//	
//	function ContactPageLink() {
//		$previousPageID = Session::get('backID');
//		if($previousPageID)
//  			{
//  			$previousPage = DataObject::get_by_id('Page', $previousPageID);
//			$myHomePage = $previousPage->Level(1);
//  			$myContactPage = DataObject::get_one("Page", "Title = 'Contact' AND ParentID = " . $myHomePage->ID, "", "");
//  			if($myContactPage) {
//  		        $myContactPageLink = $myContactPage->Link();
//  		        return $myContactPageLink;
//  			} else {
//  		        return false;
//  			}
//			
//		} else {
//			return false;
//		}
//	}
//	
//	/**
//	 * Site search form 
//	 */ 
//	function SearchForm() {
//	  	$searchText = isset($_REQUEST['Search']) ? $_REQUEST['Search'] : '';
//		$fields = new FieldSet(
//	      	new HeaderField("Zoek", 3),
//			new TextField("Search", "", $searchText)
//	  	);
//		$actions = new FieldSet(
//	      	new FormAction('results', 'Zoeken')
//	  	);
//
//	  	return new SearchForm($this, "SearchForm", $fields, $actions);
//	}
//	
//	function results($data, $form){
//		//echo 'results';exit();
//		  	$data = array(
//		     	'Results' => $form->getResults(),
//		     	'Query' => $form->getSearchQuery(),
//		      	'Title' => 'Search Results'
//		  	);
//			$myPage = new Page_Controller();
//		  	return $myPage->customise($data)->renderWith(array('Page_results', 'Page'));
//	}
//	
//	
//	function ShortNewsOverview() {
//		$previousPageID = Session::get('backID');
//		if($previousPageID)
//		{
//			$previousPage = DataObject::get_by_id('Page', $previousPageID);
//			$myHomePage = $previousPage->Level(1);
//			$myNewsHolderPage = DataObject::get_one("NewsHolder", "ParentID = " . $myHomePage->ID, "", "");
//			if($myNewsHolderPage) {
//				$myNewsHolderPageID = $myNewsHolderPage->ID;
//				$shortNewsOverview = DataObject::get("NewsPage", "ParentID = " . $myNewsHolderPageID, "Created DESC", "", "2");
//				return $shortNewsOverview;
//			} else {
//				return false;
//			}
//		} else {
//			return false;
//		}
//	}
//	
//	// get a random quote from the quotes entered in the subsite homepage
//	function Quote() {
//		$previousPageID = Session::get('backID');
//		if($previousPageID)
//		{
//			$previousPage = DataObject::get_by_id('Page', $previousPageID);
//			$myHomePage = $previousPage->Level(1);
//			$myHomePageID = $myHomePage->ID;
//			$homeQuote = DataObject::get("Quote", "PageID = " . $myHomePage->ID, "Rand()", "", "1");
//			if($homeQuote) {
//				return($homeQuote);
//			} else {
//				return false;
//			}
//		} else {
//			return false;
//		}
//	}
//	
//	public function subscribe()
//	{
//		self::$Lists = $this->SubscribedLists(FALSE);
//		$customFields = array(
//			"SubscribeForm" => $this->subscribeForm()
//		);
//		return $this->renderWith('SubscribePage', $customFields);
//	}
//	
//	public function confirmSubscription()
//	{
//		$urlConfirmCode = $this->urlConfirmCode();
//		// see if the record exists
//		if($urlConfirmCode[0] && is_numeric($urlConfirmCode[0]))
//		{
//			$myUnapprovedSubscriber = DataObject::get_by_id('SSNewsletterSubscriber_unapproved', $urlConfirmCode[0]);
//			// check if the passwords match
//			if($myUnapprovedSubscriber->Password == $urlConfirmCode[1])
//			{
//				$newsLetterList = DataObject::get_by_id('NewsLetterListPage', $myUnapprovedSubscriber->List);
//				$myApprovedSubscriber = new SSNewsletterSubscriber();
//				$myApprovedSubscriber->Email = $myUnapprovedSubscriber->Email;
//				$myApprovedSubscriber->write();
//				$newsLetterList->SSNewsletterSubscribers()->add($myApprovedSubscriber);
//				Director::redirect(Director::absoluteBaseURL() . "mailtools/subscriptionConfirmed");
//			} else {
//				Director::redirect(Director::absoluteBaseURL());
//			}
//		} else {
//			Director::redirect(Director::absoluteBaseURL());
//		}
//	}
//	
//	public function subscribeForm()
//	{
//			$validator = new RequiredFields(
//				'subscribeTo',
//				'Email'
//		      );
//
//			$SubscribedList = self::$Lists;
//			$subscribeForm = new Form(
//				$this,
//				"subscribeForm",
//				$mainFieldset = new FieldSet(
//					$emailField = new EmailField('Email', 'E-mail adres*:'),
//					$selectionField = new CheckBoxSetField('subscribeTo', 'Kies op welke lijsten je wil inschrijven*:', $SubscribedList)
//				),
//				new FieldSet(
//					new FormAction('subscribeAction', 'Inschrijven')
//				), $validator
//			);
//			$selectionField->setCustomValidationMessage('Kies minstens 1 lijst waar je op wil inschrijven.');
//			return $subscribeForm;
//	}
//	
//	public function createRandomPassword() { 
//
//	    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
//	    srand((double)microtime()*1000000); 
//	    $i = 0; 
//	    $pass = '' ; 
//
//	    while ($i <= 7) { 
//	        $num = rand() % 33; 
//	        $tmp = substr($chars, $num, 1); 
//	        $pass = $pass . $tmp; 
//	        $i++; 
//	    } 
//
//	    return $pass; 
//
//	}
//	
//	public function subscribeAction($data)
//	{
//	
//		if(isset($data['subscribeTo']))
//		{
//			foreach ($data['subscribeTo'] as $list)
//			{
//				$myList = explode('_', $list);
//				$myList = $myList[0];
//				$myListTitle = DataObject::get_by_id('NewsLetterListPage', $myList);
//				$myListTitle = $myListTitle->Title;
//				$myEmail = $data['Email'];
//				$myPassword = $this->createRandomPassword();
//				
//				$myNewSubscriber = new SSNewsletterSubscriber_unapproved;
//				$myNewSubscriber->Email = $myEmail;
//				$myNewSubscriber->List = $myList;
//				$myNewSubscriber->Password = $myPassword;
//				$myNewSubscriber->write();
//				$mySubscriberID = $myNewSubscriber->ID;
//				
//				$succes = $this->SendConfirmationMail($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID);
//				if($succes)
//				{
//					Director::redirect(Director::absoluteBaseURL() . "mailtools/subscribed");
//				} else {
//					Director::redirect(Director::absoluteBaseURL() . "mailtools/subscriptionfail");
//				}
//			}
//			
//		}
//		
//	}
//	
//
//	function SendConfirmationMail($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID) {
//			$myMail = MailTools::BuildConfirmationMail($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID);
//			$subject = $this->Title;
//			// Create new swift connection and authenticate
//			$transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 465, 'ssl');
//			$transport->setUsername(SMTPUSERNAME);
//			$transport->setPassword(SMTPPASSWORD);
//		
//			$swift = Swift_Mailer::newInstance($transport);
//			$message = new Swift_Message('Bevestig je inschrijving op de mailinglijst');
//		
//			$message->setFrom(ADMINMAIL);
//			$message->setBody($myMail["htmlMail"], 'text/html');
//			$message->setTo($myEmail);
//			$message->addPart($myMail["textMail"], 'text/plain');
//		
//			// send message 
//			if ($recipients = $swift->send($message, $failures))
//			{
//			  return true;
//			}
//			// something went wrong =(
//			else
//			{
//			  return false;
//			}
//	}
//	
//	function MailContent($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID)
//	{
//		$myContent = "
//		<h1>Bevestig je inschrijving op de SAMV-nieuwsbrief: " . $myListTitle . "</h1>
//		<p>Om je inschrijving te bevestigen klik je op deze link: <a href=\"" . Director::absoluteBaseURL() . "mailtools/confirmSubscription/" . $mySubscriberID . "_" . $myPassword  . "\">Bevestig inschrijving</a></p>";
//		$myContent = str_replace('src="assets', 'src="' . Director::absoluteBaseURL() . 'assets', $myContent);
//		return $myContent;
//	}
//	
//	
//	function MailFooter()
//	{
//			$myMailFooter =
//			'
//			</td>
//			</tr>
//
//			<tr>
//			<td width="515" height="10"> </td>
//			</tr>
//
//			<tr>
//			<td width="515" style="font-family: Verdana,arial,sans-serif; color: #343434; font-size: 14px;">
//			</td>
//			</tr>
//
//			<tr>
//			<td width="515" height="10"> </td>
//			</tr>
//
//			<tr>
//			<td width="515" style="font-family: Verdana,arial,sans-serif; color: #343434; font-size: 14px;">
//				<table width="515" align="center" bgcolor="#FFFFFF" border="0">
//					<tr>
//					<td width="395" style="font-family: Verdana,arial,sans-serif; color: #343434; font-size: 9px;" align="right" valign="top">
//						© <a href="http://www.samv.be">SAMV</a>
//					</td>
//					<td width="20"></td>
//					<td width="100">
//					<img src="' . Director::absoluteBaseURL() . 'themes/samv/images/logo_vlaamse_overheid.jpg" width="100" height="29" alt="Logo Vlaamse Overheid" />
//					</td>
//					</tr>
//				</table>
//			</td>
//			</tr>
//			</table>
//		</td>
//		</tr>
//		</table>
//		';
//		
//		return $myMailFooter;
//	
//	}
//	
//	public function BuildConfirmationMail($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID)
//	{
//		$html = NewsLetterPage_Controller::HTMLHeader();
//		$html .= NewsLetterPage_Controller::MailHeader();
//		$html .= MailTools::MailContent($myList, $myEmail, $myPassword, $myListTitle, $mySubscriberID);
//		$html .= MailTools::MailFooter();
//		$html .= NewsLetterPage_Controller::HTMLFooter();
//		
//		$convertedHTML = new CSSToInlineStyles($html, '');
//		$convertedHTML->setUseInlineStylesBlock('true');
//		$convertedHTML =  $convertedHTML->convert();
//		
//		$plainText =  NewsLetterPage_Controller::getPlainText($convertedHTML);
//		
//		$myMail = array("htmlMail" => $convertedHTML, "textMail" => $plainText);
//		return $myMail;
//	}
//	
//	public function SubscribedLists($isSubscribedTo = TRUE, $emailTocheck = '')
//	{
//		$mySubscribedLists = array();
//		if($isSubscribedTo)
//		{
//			$myEmail = self::$UnsubscribeAddress;
//		} else {
//			$myEmail = $emailTocheck;
//		}
//		// get a DataObjectSet for all Subscriber Objects
//		$mySubscribers = DataObject::get('SSNewsLetterSubscriber', "Email='$myEmail'");
//		if($mySubscribers)
//		{
//			$mySubscribers = $mySubscribers->getIterator();
//			foreach($mySubscribers as $subscriber)
//			{
//				// decide if you want to search for subscribed or unsubscibed to
//				if($isSubscribedTo)
//				{
//					$whereClause = "SSNewsletterSubscriberID = $subscriber->ID";
//				} else {
//					$whereClause = "SSNewsletterSubscriberID != $subscriber->ID";
//				}
//				
//				// get all connections in the DB
//				$myQuery = new SQLQuery(
//					$select = "*",
//					$from = array("NewsLetterListPage_SSNewsletterSubscribers"),
//					// return either subscribedTo or not subscribedTo lists
//					$where = $whereClause,
//					$orderby = "",
//					$groupby = "",
//					$having = "",
//					$limit = ""
//				);
//				$result = $myQuery->execute();
//				// build the array for subscribed lists
//				foreach($result as $row) {
//					$subscriberID = $row['SSNewsletterSubscriberID'];
//					$newsletterList = DataObject::get_by_id("Page", $row['NewsLetterListPageID']);
//					if($newsletterList)
//					{
//						$mySubscribedLists[$newsletterList->ID . '_' . $subscriberID] = $newsletterList->Title;
//					}
//				}
//			}
//			return $mySubscribedLists;
//		// return all non-subscribed 
//		} else if(!$isSubscribedTo) {
//			$allLists = DataObject::get('NewsletterListPage');
//			foreach($allLists as $row) {
//				$mySubscribedLists[$row->ID . '_0'] = $row->Title;
//			}
//			return $mySubscribedLists;
//		} else {
//			return false;
//		}
//	}
//	
//	
//	protected function unsubscribeForm()
//	{
//			$validator = new RequiredFields(
//				'UnsubScribeFrom'
//		      );
//	
//			$SubscribedList = self::$Lists;
//			$unsubscribeForm = new Form(
//				$this,
//				"unsubscribeForm",
//				$mainFieldset = new FieldSet(
//					$selectionField = new CheckBoxSetField('UnsubScribeFrom', 'Je bent geabonneerd op onderstaande deze lijsten. Vink aan bij welke je jezelf wil uitschrijven:', $SubscribedList)
//				),
//				new FieldSet(
//					new FormAction('unsubscribeAction', 'Uitschrijven')
//				), $validator
//			);
//			$selectionField->setCustomValidationMessage('Kies minstens 1 lijst waar je jezelf van wil uitschrijven.');
//			return $unsubscribeForm;
//	}
//	
//	public function unsubscribeAction($data)
//	{
//		if(isset($data['UnsubScribeFrom']))
//		{
//			//echo 'must delete must delete';
//			foreach($data['UnsubScribeFrom'] as $newsletterConnection)
//			{
//				$newsletterConnection = explode("_", $newsletterConnection);
//				$newsletterID = $newsletterConnection[0];
//				$subscriberID = $newsletterConnection[1];
//				
//				$myQuery = new SQLQuery(
//					$select = "*",
//					$from = array("NewsLetterListPage_SSNewsletterSubscribers"),
//					$where = "SSNewsletterSubscriberID = $subscriberID AND NewsLetterListPageID = $newsletterID"
//				);
//				$myQuery->delete = true;
//				$result = $myQuery->execute();
//			}
//		}
//		Director::redirect(Director::absoluteBaseURL() . "mailtools/unsubscribed");
//	}
//	
//	public function unsubscribed()
//	{
//		return $this->renderWith('Unsubscribed');
//	}
//	
//	public function subscribed()
//	{
//		return $this->renderWith('Subscribed');
//	}
//	
//	public function subscriptionConfirmed()
//	{
//		return $this->renderWith('SubscriptionConfirmed');
//	}
//	
//	public function subscriptionfail()
//	{
//		return $this->renderWith('SubscriptionFail');
//	}
//	
//	
//}