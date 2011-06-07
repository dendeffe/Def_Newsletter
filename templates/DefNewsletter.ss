$RedirectNonAdmins
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<% base_tag %>

<title>$Title - $SiteConfig.Title</title>
<% if IsAdmin %>
	$HTMLHeader
	<div id="mailPreviewHeader"><h1>Newsletter Preview: <span>$Title</span></h1></div>
	
	<div id="mailPreview">
		<div id="mailContentContainer">
			$MailContentPreview
		</div>
	</div>
	
	<div id="mailInfo">
		<div id="mailMeta">
			<% if AlreadySent %>
				<h4 id="status" class="sent">Sent</h4>
			<% else %>
				<h4 id="status" class="notSent">Not sent</h4>
			<% end_if %>
			<div id="mailingLists">
				<h5>Mailing lists:</h5>
				<% if Lists %>
					<ul>
					<% control Lists %>
					<li>$Title</li>
					<% end_control %>
					</ul>
				<% else %>
				<p>No mailing lists selected</p>
				<% end_if %>
			</div>
			<div id="mailAdresses">
				<h5>Email addresses:</h5>
				$DisplayRecipients
			</div>
		</div>
		<div id="mailActions">
			<div id="testmail">
			<h4>Send a testmail:</h4>
			$TestMailForm
			</div>
			<div id="entireList">
			$SendEntireListForm
			</div>
		</div>
		<div id="dialogModal" title="Are you sure you want to do this?">
			<p>This will send the newsletter to all subscribers.</p>
		</div>
		<div id="dialogConfirm" title="">
			<p>The email was sent to all subscribers.</p>
		</div>
		<div id="dialogTestmailConfirm" title="">
			<p>Testmail was sent.</p>
		</div>
	</div><!-- #mailInfo -->
	$HTMLFooter
<% end_if %>

</body>
</html>