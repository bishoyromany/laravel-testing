<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\CurrencyService;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_convert_usd_to_eur_successfully()
    {

        $result = CurrencyService::convert(100, 'usd', 'eur');
        $this->assertEquals(98, $result);
    }

        /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_convert_usd_to_gbp_failure()
    {

        $result = CurrencyService::convert(100, 'usd', 'gbp');
        $this->assertEquals(0, $result);
    }
}
