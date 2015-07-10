<?php
return array(
    'routing' => array(
        array('name' => 'main_index', 'pattern' => '/', 'controller' => 'Main', 'action' => 'index'),
        array('name' => 'main_beton', 'pattern' => '/beton', 'controller' => 'Main','action' => 'beton'),
	array('name' => 'main_about', 'pattern' => '/about', 'controller' => 'Main','action' => 'about'),
	array('name' => 'main_contacts', 'pattern' => '/contacts', 'controller' => 'Main','action' => 'contacts'),
	array('name' => 'main_price', 'pattern' => '/price', 'controller' => 'Main','action' => 'price'),
        array('name' => 'guestbook_list', 'pattern' => '/guestbook', 'controller' => 'Guestbook', 'action' => 'list'),
        array('name' => 'guestbook_add', 'pattern' => '/guestbook/add', 'controller' => 'Guestbook', 'action' => 'add'),
        array('name' => 'guestbook_delete', 'pattern' => '/guestbook/:id/delete', 'controller' => 'Guestbook', 'action' => 'delete'),
        array('name' => 'req_print', 'pattern' => '/req/:text', 'controller' => 'Req', 'action' => 'print'),
    ),
    'entities' => array(
        'App\Entity\Message' => array ('repository' => 'App\Entity\MessageRepository'),
    )
);
