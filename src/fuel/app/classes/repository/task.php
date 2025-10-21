<?php

class Task_Repository extends Repository_Base
{
    public function getModel()
    {
        return Model_Task::class;
    }
}