<?php
namespace App\Entity;
use App\Kernel\Repository\FilesystemRepository;

class MessageRepository extends FilesystemRepository
{
  public function getAllSortedByDate()
  {
    $entities = $this->getAll();
    
    usort($entities, function($a, $b){
      $ad = $a->getDatetime();
      $bd = $b->getDatetime();
      return $ad < $bd ? -1 : ($ad==$bd ? 0 : 1);
    });
    
    return $entities;
  }
}
