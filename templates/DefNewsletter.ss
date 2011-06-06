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
	</div><!-- #mailInfo -->
	$HTMLFooter
<% end_if %>

</body>
</html>