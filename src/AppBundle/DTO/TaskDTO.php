<?php


namespace AppBundle\DTO;

use AppBundle\Entity\Task;

class TaskDTO
{
    public $title;
    public $content;

    public function createFromTask(Task $task)
    {
        $this->title = $task->getTitle();
        $this->content = $task->getContent();
    }
}
