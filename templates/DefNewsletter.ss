$RedirectNonAdmins

<% if IsAdmin %>
	$HTMLHeader
	<h1>Preview</h1>
	<div id="mailPreview">
	$MailContentPreview
	</div>
	<div id="mailInfo">
		<div id="mailMeta">
			<% if AlreadySent %>
				<h4 id="status" class="sent">This mail has already been sent.</h4>
			<% else %>
				<h4 id="status" class="notSent">This mail has not yet been sent.</h4>
			<% end_if %>
			<h4>This e-mail is coupled to these mailing lists:</h4>
			<% if Lists %>
				<ul>
				<% control Lists %>
				<li>$Title</li>
				<% end_control %>
				</ul>
			<% else %>
			<p>No mailing lists selected</p>
			<% end_if %>
			<h4>And these Email adresses:</h4>
			$DisplayRecipients
		</div>
		<div id="mailActions">
		<h4>Send a testmail:</h4>
		$TestMailForm
		<h4>Send to the entire list:</h4>
		$SendEntireListForm
		</div>
	</div><!-- #mailInfo -->
	$HTMLFooter
<% end_if %>