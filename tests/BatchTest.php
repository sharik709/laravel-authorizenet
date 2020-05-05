<?php

namespace ANet\Tests;

use ANet\Transactions\Batch;
use Exception;
use net\authorize\api\contract\v1\BatchStatisticType;

class BatchTest extends BaseTestCase
{

    public function testValidate_should_pass()
    {
        $batch = new Batch();
        $this->assertNull($batch->validate('2019-01-01', '2019-01-29'));
    }

    public function testValidate_should_fail()
    {
        $this->expectException(Exception::class);
        $batch = new Batch();
        $batch->validate('2019-01-01', '2019-02-30');
    }

    public function testGetSettledBatchList()
    {
        $batch = new Batch();
        $firstDate = '2019-03-01';
        $secondDate = '2019-03-29';
        $list = $batch->getSettledBatchList($firstDate, $secondDate, true);
        $this->assertCount(0, $list);
    }

    public function testNormalizeBatchStatistics()
    {
        $stat = new BatchStatisticType();
        $stat->setAccountType('test');
        $stat->setChargeAmount(rand(100, 200));
        $stat->setChargeCount(rand(1, 3));
        $stat->setRefundAmount(rand(10, 20));
        $stat->setVoidCount(0);
        $stat->setDeclineCount(rand(0, 10));
        $stat->setErrorCount(0);
        $batch = new Batch();
        $normalizedStats = $batch->normalizeBatchStatistics([$stat])[0];

        $this->assertEquals($stat->getAccountType(), $normalizedStats['accountType']);
        $this->assertEquals($stat->getChargeAmount(), $normalizedStats['chargeAmount']);
        $this->assertEquals($stat->getChargeCount(), $normalizedStats['chargeCount']);
        $this->assertEquals($stat->getRefundAmount(), $normalizedStats['refundAmount']);
        $this->assertEquals($stat->getVoidCount(), $normalizedStats['voidCount']);
        $this->assertEquals($stat->getDeclineCount(), $normalizedStats['declineCount']);
        $this->assertEquals($stat->getErrorCount(), $normalizedStats['errorCount']);
    }
}
