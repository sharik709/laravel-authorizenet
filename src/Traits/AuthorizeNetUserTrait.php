<?php namespace ANet\Traits;

use ANet\ANet;

trait AuthorizeNetUserTrait
{
    /**
     * It will return instance of a operations class
     * @return ANet
     */
    public function anet()
    {
        return new ANet($this);
    }
}