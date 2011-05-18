<?php

class Def_Newsletter extends Page {
	static $db = array(
        'AlreadySent' => 'Boolean'
	);
	
	static $many_many = array(
        'Def_Newsletter_SubscriberLists' => 'Def_Newsletter_SubscriberList'
	);
	
	public function getCMSFields() {
        $f = parent::getCMSFields();

        // Add the newsletter list management
		$listField = new ManyManyDataObjectManager(
			$this,
			'Def_Newsletter_SubscriberLists',
			'Def_Newsletter_SubscriberList',
			array(
			       'Title' => 'Title'
			), 'getCMSFields_forPopup'
		);
        $listField->setAddTitle('Lijsten');
        $f->addFieldToTab('Root.Content.Lijsten', $listField);
        return $f;
	}
}

class Def_Newsletter_Controller extends Page_Controller {
}

//class NewsLetterPage Extends Page {
//	static $singular_name = "Nieuwsbrief";
//	
//	static $db = array(
//		'AlreadySent' => 'Boolean'
//	);
//	
//	static $many_many = array(
//		'NewsLetterListPages' => 'NewsLetterListPage'
//	);
//	
//	public function getCMSFields() {
//		$f = parent::getCMSFields();
//		$f->removeByName("Important");
//		$f->removeByName("Quotes");
//		/*echo(NewsLetterPage_Controller::AlreadySent());exit();
//		// Copy the AlreadySent status
//		$AlreadySentCheckbox = new CheckBoxField('AlreadySent', 'AlreadySent', NewsLetterPage_Controller::AlreadySent());
//		$f->addFieldToTab('Root.Content.Main', $AlreadySentCheckbox, 'Content');
//		*/
//		// Add the newsletter lis management
//		$listField = new ManyManyDataObjectManager(
//		$this,
//		'NewsLetterListPages',
//		'NewsLetterListPage',
//		array(
//		       'Title' => 'Title'
//		), 'getCMSFields_forPopup');
//		$listField->setAddTitle('Lijsten');
//
//		$f->addFieldToTab('Root.Content.Lijsten', $listField);
//		
//		return $f;
//	}
//	
//}
//
//class NewsLetterPage_Controller Extends Page_Controller {
//	
//	function RedirectNonAdmins()
//	{
//		if(!$this->IsAdmin()) {
//			Director::redirect(Director::baseURL());
//		}
//	}
//	
//	function IsAdmin() 
//	{
//		$currentUser = new Member();
//		$currentUser = $currentUser->currentUser();
//		if($currentUser)
//		{
//			return $currentUser->isAdmin();
//		} else {
//			return false;
//		}
//		
//	}
//	
//	function AlreadySent()
//	{
//		$myPage = Versioned::get_latest_version('NewsLetterPage', $this->ID);
//		//echo ($myPage->AlreadySent);exit();
//		if($myPage->AlreadySent == 1)
//		{
//			return 1;
//		} else {
//			return 0;
//		}
//	}
//	
//	function getPlainText($text, $includeAHrefs = false, $includeImgAlts = false)
//	{
//		// replace break rules with a new line and make sure a paragraph also ends with a new line
//		$text = str_replace('<br />', PHP_EOL, $text);
//		$text = str_replace('</p>', '</p>'. PHP_EOL, $text);
//
//		// remove tabs
//		$text = str_replace("\t", '', $text);
//
//		// remove the head- and style-tags and all their contents
//		$text = preg_replace('|\<head.*\>(.*\n*)\</head\>|isU', '', $text);
//		$text = preg_replace('|\<style.*\>(.*\n*)\</style\>|isU', '', $text);
//
//		// replace links with the inner html of the link with the url between ()
//		// eg.: <a href="http://site.domain.com">My site</a> => My site (http://site.domain.com)
//		if($includeAHrefs) $text = preg_replace('|<a.*href="(.*)".*>(.*)</a>|isU', '$2 ($1)', $text);
//
//		// replace images with their alternative content
//		// eg. <img src="path/to/the/image.jpg" alt="My image" /> => My image
//		if($includeImgAlts) $text = preg_replace('|\<img[^>]*alt="(.*)".*/\>|isU', '$1', $text);
//
//		// strip tags
//		$text = strip_tags($text);
//
//		// replace 'line feed' characters with a 'line feed carriage return'-pair
//		$text = str_replace("\n", "\n\r", $text);
//
//		// replace double, triple, ... line feeds to one new line
//		$text = preg_replace('/(\n\r)+/', PHP_EOL, $text);
//
//		// decode html entities
//		$text = html_entity_decode($text);
//
//		// return the plain text
//		return $text;
//	}
//	
//	function HTMLHeader()
//	{
//		$myHTMLHeader = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
//		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
//		
//		<head>
//			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
//			<link href="' . Director::absoluteBaseURL() . '/themes/samv/css/mailInfo.css" media="screen, projection" rel="stylesheet" type="text/css" />
//			
//			<title>' . $this->Title . '</title>
//			
//		</head>
//		
//		<body>';
//		return $myHTMLHeader;
//	}
//
//	
//	function MailHeader()
//	{
//		$myMailHeader = '
//			<style>
//				h1
//				{
//					color: #BC243B;
//					font-size: 28px;
//					font-family: Verdana,arial,sans-serif;
//					font-weight: bold;
//				}
//				h2
//				{
//					color: #BC243B;
//					font-size: 24px;
//					font-family: Verdana,arial,sans-serif;
//					font-weight: bold;
//				}
//				h3
//				{
//					color: #BC243B;
//					font-size: 20px;
//					font-family: Verdana,arial,sans-serif;
//					font-weight: bold;
//				}
//				h4
//				{
//					color: #BC243B;
//					font-size: 18px;
//					font-family: Verdana,arial,sans-serif;
//					font-weight: bold;
//				}
//				h5
//				{
//					color: #BC243B;
//					font-size: 16px;
//					font-family: Verdana,arial,sans-serif;
//					font-weight: bold;
//				}
//				h6
//				{
//					color: #BC243B;
//					font-size: 14px;
//					font-family: Verdana,arial,sans-serif;
//					font-weight: bold;
//				}
//				a
//				{
//					color: #BC243B;
//					font-family: Verdana,arial,sans-serif;
//				}
//				table
//				{
//					color: #343434;
//					font-family: Verdana,arial,sans-serif;
//					font-size: 14px;
//				}
//				p
//				{
//					color: #343434;
//					font-family: Verdana,arial,sans-serif;
//					font-size: 14px;
//				}
//				img
//				{
//					border: 0;
//				}
//				.orderOverview td
//				{
//					border: 1px solid #000;
//				}
//			</style>
//			<table width="100%" cellpadding="10" cellspacing="0" align="center">
//			<tr>
//			<td>	
//				<table width="515" align="center" bgcolor="#FFFFFF" border="0">
//				<tr>
//				<td width="515">
//				<a href="http://www.samv.be"><img src="' . Director::absoluteBaseURL() . 'themes/samv/images/newsletterHeader.jpg" width="515" height="88" alt="SAMV: Steunpunt allochtone meisjes en vrouwen" border="0" /></a>
//				</td>
//				</tr>
//
//				<tr>
//				<td width="515" height="10"> </td>
//				</tr>
//
//				<tr>
//				<td width="515" style="font-family: Verdana,arial,sans-serif; color: #343434; font-size: 14px;">
//		';
//		
//		return $myMailHeader;
//	}
//	
//	function MailContent()
//	{
//		$myContent = $this->Content;
//		$myContent = str_replace('src="assets', 'src="' . Director::absoluteBaseURL() . 'assets', $myContent);
//		return $myContent;
//	}
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
//						© <a href="http://www.samv.be">SAMV</a> - <a href="' . Director::absoluteBaseURL() . 'mailtools/unsubscribe/-unsublink-">Schrijf je uit</a>
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
//	function HTMLFooter()
//	{
//		$myHTMLFooter = '
//			</body>
//			</html>
//		';
//		return $myHTMLFooter;
//	}
//	
//	function BuildMail()
//	{
//		$html = $this->HTMLHeader();
//		$html .= $this->MailHeader();
//		$html .= $this->MailContent();
//		$html .= $this->MailFooter();
//		$html .= $this->HTMLFooter();
//		
//		$convertedHTML = new CSSToInlineStyles($html, '');
//		$convertedHTML->setUseInlineStylesBlock('true');
//		$convertedHTML =  $convertedHTML->convert();
//		
//		$plainText =  $this->getPlainText($convertedHTML);
//		
//		$myMail = array("htmlMail" => $convertedHTML, "textMail" => $plainText);
//		return $myMail;
//	}
//	
//	
//	function TestMailForm(){
//		$testmailForm = new Form(
//			$this,
//			"TestMailForm",
//			$mainFieldset = new FieldSet(
//				$howManyField = new EmailField("TestEmail", "E-mailadres")
//			),
//			new FieldSet(
//				new FormAction('SendTestMail', 'Versturen')
//			)
//		);
//		return $testmailForm;
//	}
//	
//	
//	function SendTestMail($data) {
//		if($this->IsAdmin()) {
//			$myMail = $this->BuildMail();
//			$subject = $this->Title;
//			// Create new swift connection and authenticate
//			$transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 465, 'ssl');
//			$transport->setUsername(SMTPUSERNAME);
//			$transport->setPassword(SMTPPASSWORD);
//		
//			$swift = Swift_Mailer::newInstance($transport);
//			$message = new Swift_Message($this->Title . ' (testmail)');
//		
//			$message->setFrom(ADMINMAIL);
//			$message->setBody($myMail["htmlMail"], 'text/html');
//			$message->setTo($data["TestEmail"]);
//			$message->addPart($myMail["textMail"], 'text/plain');
//		
//			// send message 
//			if ($recipients = $swift->send($message, $failures))
//			{
//			  // This will let us know how many users received this message
//			  // If we specify the names in the X-SMTPAPI header, then this will always be 1.  
//			  //echo 'Message sent out to '.$recipients.' users';
//			}
//			// something went wrong =(
//			else
//			{
//			  echo "Something went wrong - ";
//			  print_r($failures);
//				exit();
//			}
//		
//			Director::redirectBack();
//		} else {
//			Director::redirect(Director::baseURL());
//		}
//	}
//	
//	function SendEntireListForm(){
//		$sendEntireListForm = new Form(
//			$this,
//			"sendEntireListForm",
//			$mainFieldset = new FieldSet(
//			),
//			new FieldSet(
//				new FormAction('SendEntireList', 'Versturen')
//			)
//		);
//		return $sendEntireListForm;
//	}
//	
//	function SendEntireList() {
//		if($this->IsAdmin()) {
//			$myMail = $this->BuildMail();
//			$subject = $this->Title;
//			
//			$myRecipients = $this->Recipients();
//			$myUnsubscribeLinks = $this->UnsubscribeLinks($myRecipients);
//			
//			
//			// PREPARE SPECIAL HEADER
//			$hdr = new SmtpApiHeader();
//			$toList = $this->Recipients();
//			
//			$hdr->addTo($toList);
//			$hdr->addSubVal('-unsublink-', $myRecipients);
//			
//			// Create new swift connection and authenticate
//			$transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 465, 'ssl');
//			$transport->setUsername(SMTPUSERNAME);
//			$transport->setPassword(SMTPPASSWORD);
//		
//			$swift = Swift_Mailer::newInstance($transport);
//			$message = new Swift_Message($subject);
//		
//			$headers = $message->getHeaders();
//			$headers->addTextHeader('X-SMTPAPI', $hdr->asJSON());
//		
//			$message->setFrom(ADMINMAIL);
//			$message->setBody($myMail["htmlMail"], 'text/html');
//			$message->setTo(ADMINMAIL);
//			$message->addPart($myMail["textMail"], 'text/plain');
//		
//
//		
//			// send message 
//			if ($recipients = $swift->send($message, $failures))
//			{
//			  // This will let us know how many users received this message
//			  // If we specify the names in the X-SMTPAPI header, then this will always be 1.  
//			  //echo 'Message sent out to '.$recipients.' users';
//			}
//			// something went wrong =(
//			else
//			{
//			  echo "Something went wrong - ";
//			  print_r($failures);
//				exit();
//			}
//			
//			$myOwnPage = DataObject::get_by_id('NewsLetterPage', $this->ID);
//			$myOwnPage->AlreadySent = 1;
//			$myOwnPage->write();
//			
//			Director::redirectBack();
//		} else {
//			Director::redirect(Director::baseURL());
//		}
//		
//	}
//	
//	function Lists()
//	{
//		$myLists = $this->getManyManyComponents('NewsLetterListPages');
//		$myLists = $myLists->items;
//		$myLists = new DataObjectSet($myLists);
//		return $myLists;
//	}
//	
//	function UnsubscribeLinks($MyRecipients)
//	{
//		$myUnsubscribeLinks = array();
//		for($x = 0; $x < count($MyRecipients); $x++)
//		{
//			$myUnsubscribeLinks[$x] = Director::absoluteBaseURL() . "mailtools/unsubscribe/" . $MyRecipients[$x]; 
//		}
//		return $myUnsubscribeLinks;
//	}
//	
//	function Recipients()
//	{
//		$emailsOnList = array();
//		$myLists = $this->getManyManyComponents('NewsLetterListPages');
//		$myLists = $myLists->items;
//		// loop through mailing lists
//		for($x = 0; $x < count($myLists); $x++)
//		{
//			$myEmails = $myLists[$x]->getManyManyComponents('SSNewsletterSubscribers');
//			$myEmails = $myEmails->items;
//			// loop emails
//			for($y = 0; $y < count($myEmails); $y++) {
//				$emailsOnList[] = $myEmails[$y]->Email;
//			}
//		}
//		
//		$emailsOnList = array_unique($emailsOnList);
//		return $emailsOnList;
//	}
//	
//	
//	function DisplayRecipients()
//	{
//		$myRecipients = $this->Recipients();
//		if(count($myRecipients)) {
//			$myDisplayRecipients = "<ul>";
//			for($x = 0; $x < count($myRecipients); $x++)
//			{
//				$myDisplayRecipients .= "<li>" . $myRecipients[$x] . "</li>";
//			}
//			$myDisplayRecipients .= "</ul>";
//		} else {
//			$myDisplayRecipients = "<p>Geen emailadressen</p>";
//		}
//		return $myDisplayRecipients;
//	}
//	
//	function MailTitle()
//	{
//		return $this->Title;
//	}
//	
//}