<!DOCTYPE html>
<html lang="en">
  <head>
		<% base_tag %>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Uitschrijven nieuwsbrief - $SiteConfig.Title: $SiteConfig.Tagline</title>
		<link rel="stylesheet" href="{$BaseHref}themes/samv/css/screen.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="{$BaseHref}themes/samv/css/typography_small.css" type="text/css" media="screen" class="changeme" />
		<link rel="stylesheet" href="{$BaseHref}themes/samv/css/print.css" type="text/css" media="print" />
		<!--[if IE 7]><link rel="stylesheet" href="{$BaseHref}themes/samv/css/ie7.css" type="text/css" media="screen" title="no title" charset="utf-8"><![endif]-->
		<!--[if lte IE 6]><link rel="stylesheet" href="{$BaseHref}themes/samv/css/ie6.css" type="text/css" media="screen" title="no title" charset="utf-8" /><![endif]-->
		<link rel="shortcut icon" href="$BaseHref/favicon.ico" />
		<link rel="shortcut icon" href="$BaseHref/favicon.gif" type="image/gif" />
		
	</head>
<body>
	<div id="container">
	<% include Menu %>
	<div id="mainContent">
		<div id="mainText">
		<h3>Je wil uitschrijven met dit adres: $UnsubscribeAddress</h3>
		<% if SubscribedLists %>
		$UnsubscribeForm
		<% else %>
		<p>Je bent volgens ons op geen enkele nieuwsbrief ingeschreven. Denk je dat dit toch het geval is, <a href="mailto:$AdminMail">neem dan contact met ons op</a>.</p>
		<% end_if %>
		</div>
	</div><!-- mainContent -->
	</div>
</body>
</html>