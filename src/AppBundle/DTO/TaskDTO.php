<?php


namespace AppBundle\DTO;


use AppBundle\Entity\Task;

class TaskDTO
{
    public $title;
    public $content;

    public function createFromTask(Task $user)
    {
        $this->title = $user->getTitle();
        $this->content = $user->getContent();
    }
}