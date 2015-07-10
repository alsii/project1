<?php
namespace App\Entity;

class Message
{
    private $identifier;
    private $user;
    private $datetime;
    private $text;
    
    public function __construct()
    {
        $this->identifier = sha1(microtime());
    }
    
    public function getId()
    {
        return $this->identifier;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function setDatetime($datetime)
    {
        $this->datetime = $datetime ;
        return $this;
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}
