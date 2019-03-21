<?php
namespace ANet\Traits;

use ANet\ANet;

trait ANetPayments
{

    /**
     * @return ANet
     */
    public function anet()
    {
        return new ANet($this);
    }


}
