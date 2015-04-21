<?php
return array(
    'routing' => array(
        array('pattern' => '/', 'controller' => 'Main', 'action' => 'index'),
        array('pattern' => '/guestbook', 'controller' => 'Guestbook', 'action' => 'list'),
        array('pattern' => '/guestbook/add', 'controller' => 'Guestbook', 'action' => 'add'),
        array('pattern' => '/guestbook/:id/delete', 'controller' => 'Guestbook', 'action' => 'delete'),
        array('pattern' => '/req/:text', 'controller' => 'Req', 'action' => 'print'),
    ),
    'entities' => array(
        array('entity' = 'App\Entity\Message', 'repository' => 'App\Entity\MessageRepository'),
    )
);
