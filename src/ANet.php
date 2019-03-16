<?php namespace ANet;

class ANet
{
    protected $user;

    /**
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * It will set the user
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * It will get user
     * @return model
     */
    public function getUser()
    {
        return $this->user;
    }





}