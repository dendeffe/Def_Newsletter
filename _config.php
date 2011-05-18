<?php
Director::addRules(100, array(
	'newslettertools//$Action' => 'Def_NewsLetter_Tools'
));


/*


/* THE MANYMANY DOMS DON'T WORK PROPERLY WITHOUT THIS */
//SortableDataObject::add_sortable_many_many_relation('Def_Newsletter_SubscriberList', 'Def_Newsletter_Subscribers');
//SortableDataObject::add_sortable_many_many_relation('Def_Newsletter', 'Def_Newsletter_SubscriberLists');