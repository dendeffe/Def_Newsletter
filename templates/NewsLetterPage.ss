$RedirectNonAdmins

<% if IsAdmin %>
	$HTMLHeader
	<h1 id="mailPreview">Voorvertoning</h1>
	
	$MailHeader
	$MailContent
	$MailFooter

	<div id="mailInfo">
		<div id="mailMeta">
			<% if AlreadySent %>
				<h4 id="status" class="sent">Reeds verstuurd</h4>
			<% else %>
				<h4 id="status" class="notSent">Nog niet verstuurd</h4>
			<% end_if %>
			<h4>Gekoppeld aan deze maillijsten</h4>
			<% if Lists %>
				<ul>
				<% control Lists %>
				<li>$Title</li>
				<% end_control %>
				</ul>
			<% else %>
			<p>Geen maillijsten</p>
			<% end_if %>
			<h4>En deze emailadressen</h4>
			$DisplayRecipients
		</div>
		<div id="mailActions">
		<h4>Stuur een testmail naar dit adres</h4>
		$TestMailForm
		<h4>Stuur de nieuwsbrief naar de volledige lijst</h4>
		$SendEntireListForm
		</div>
	</div><!-- #mailInfo -->
	
	$HTMLFooter
<% end_if %>