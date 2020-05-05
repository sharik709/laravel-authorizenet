<?php

namespace ANet\Transactions;

use ANet\AuthorizeNet;
use Carbon\Carbon;
use Exception;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\contract\v1\BatchStatisticType;
use net\authorize\api\controller as AnetController;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Batch extends AuthorizeNet
{
    /**
     * It will return all settled batches
     * @param string $firstSettlementDate
     * @param string $lastSettlementDate
     * @param bool $includeStatistics
     * @return array|null
     * @throws Exception
     */
    public function getSettledBatchList(string $firstSettlementDate, string $lastSettlementDate, $includeStatistics = true)
    {
        $this->validate($firstSettlementDate, $lastSettlementDate);

        $request = new AnetAPI\GetSettledBatchListRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setIncludeStatistics($includeStatistics);
        $request->setFirstSettlementDate(Carbon::parse($firstSettlementDate)->toDateTime());
        $request->setLastSettlementDate(Carbon::parse($lastSettlementDate)->toDateTime());

        $controller = new AnetController\GetSettledBatchListController ($request);

        $response = $this->execute($controller);

        if (strtolower($response->getMessages()->getMessage()[0]->getText()) == 'no records found.') {
            return [];
        }

        if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
            return array_map(function ($batch) {
                $batch = $this->normalizeBatchToArray($batch);
//                $stats = $this->normalizeBatchStatistics($batch->getStatistics());
                $batch['statistics'] = $batch->getStatistics();
                return $batch;
            }, $response->getBatchList());
        }

        return [
            'error' => 'Failed to get batch list',
            'response' => $response
        ];
    }

    /**
     * It validates if given values are what it should be
     * @param $firstDate
     * @param $secondDate
     * @throws Exception
     */
    public function validate($firstDate, $secondDate): void
    {
        $firstDate = Carbon::parse($firstDate);
        $secondDate = Carbon::parse($secondDate);
        $days = $firstDate->diffInDays($secondDate);
        if ($days > 31) {
            throw new Exception('Settlement Date cannot be larger than 31 Days. Given dates ' . $firstDate->locale() . ' to ' . $secondDate->locale());
        }
    }

    /**
     * @param $batch
     * @return array
     * @throws ReflectionException
     */
    public function normalizeBatchToArray($batch)
    {
        $mapBatch = [];
        $ref = new ReflectionClass($batch);
        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();
            if (preg_match('/^get.*/', $methodName)) {
                try {
                    $res = $batch->$methodName();
                    $prop = preg_split('/^get/', $methodName)[1];
                    $mapBatch[lcfirst($prop)] = $res;
                } catch (Exception $ex) {
                }
            }
        }
        return $mapBatch;
    }

    /**
     * It will normalize given batch statistics and convert to array
     * @param BatchStatisticType $batchStats
     */
    public function normalizeBatchStatistics($batchStats)
    {
        $mapStats = [];
        foreach ($batchStats as $i => $stats) {
            $ref = new ReflectionClass($stats);
            foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $methodName = $method->getName();
                if (preg_match('/^get.*/', $methodName)) {
                    try {
                        $res = $batchStats[$i]->$methodName();
                        $prop = preg_split('/^get/', $methodName)[1];
                        $mapStats[$i][lcfirst($prop)] = $res;
                    } catch (Exception $ex) {
                    }
                }
            }
        }
        return $mapStats;
    }
}
