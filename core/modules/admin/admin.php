<?php

class admin extends  Replica
{
    private $_query_string = 'admin_do';

    /**
     *
     */
    public function __construct()
    {

    throw new Exception("The administration module cannot be used", 500);

    }





}