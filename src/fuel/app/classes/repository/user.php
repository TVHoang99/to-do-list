<?php

class Repository_User extends Repository_Base
{
    public function getModel()
    {
        return Model_User::class;
    }
}