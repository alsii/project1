<?php
return array(
 'routing' => array(
    array('/', 'Main', 'index'),
    array('/guestbook', 'Guestbook', 'list'),
    array('/guestbook/add', 'Guestbook', 'add'),
    array('/guestbook/:id/delete', 'delete'),
 )
)