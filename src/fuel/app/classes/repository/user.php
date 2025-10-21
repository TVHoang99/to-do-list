<?php

class Repository_User extends Repository_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel()
    {
        return Model_User::class;
    }
}