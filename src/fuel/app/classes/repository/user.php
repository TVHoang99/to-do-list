<?php

class User_Repository extends Repository_Base
{
    public function getModel()
    {
        return Model_User::class;
    }
}