<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneHourElectricityTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexForPanelWithElectricity()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial='.$panel->serial);

        $response->assertStatus(200);

        $this->assertCount(1, json_decode($response->getContent()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexForPanelWithoutElectricity()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial='.$panel->serial);

        $response->assertStatus(200);

        $this->assertCount(0, json_decode($response->getContent()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexWithoutExistingPanel()
    {
        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial=testserial');

        $response->assertStatus(404);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexWithoutPanelSerial()
    {
        $response = $this->json('GET', '/api/one_hour_electricities');

        $response->assertStatus(404);
    }

    public function testStoreCreatesNewOneElectricityRecord()
    {
        $serialNo = "51XT33NCHARACTRS";
        factory(Panel::class)->create([
            'serial' => $serialNo,
            'longitude' => "-134.764346",
            'latitude'  => "67.934623"
        ]);

        $response = $this->json('POST', "/api/one_hour_electricities?panel_serial=$serialNo", [
            'kilowatts' => "23.7",
            'hour'      => "2018-09-25 19:45:26",
        ]);

        $responseBody = json_decode($response->content(), true);

        $this->assertEquals(23.7, $responseBody['kilowatts']);
        $this->assertEquals("2018-09-25 19:45:26", $responseBody['hour']);
    }

    public function testStoreFailsWithInvalidOneElectricityData()
    {
        $serialNo = "51XT33NCHARACTRS";
        factory(Panel::class)->create([
            'serial' => $serialNo,
            'longitude' => "-134.764346",
            'latitude'  => "67.934623"
        ]);

        $response = $this->json('POST', "/api/one_hour_electricities?panel_serial=$serialNo", [
            'hour'      => "2018-09-25 19:45:26",
        ]);

        $responseBody = json_decode($response->content(), true);

        $this->assertContains("The kilowatts field is required.", $responseBody);
    }

    public function test24HourElectricityReportForPanel()
    {
        $panel = factory(Panel::class)->create([
            'serial' => "51XT33NCHARACTRS",
            'longitude' => "-134.764346",
            'latitude'  => "67.934623"
        ]);

        $this->makePanelHourlyElectricities($panel->id);

        $response = $this->json('GET', "/api/one_day_electricities?panel_serial=$panel->serial");
        $responseBody = json_decode($response->content(), true);

        $this->assertEquals(68, $responseBody['sum']);
        $this->assertEquals(7, $responseBody['min']);
        $this->assertEquals(11, $responseBody['max']);
        $this->assertEquals(8.5, $responseBody['average']);
    }

    public function makePanelHourlyElectricities(int $panelId)
    {
        // add five OneHourElectricities for yesterday
        factory(OneHourElectricity::class, 5)->create([
            'panel_id' => $panelId,
            'kilowatts' => rand(3, 20),
            'hour' => Carbon::today()->subHours(rand(3, 20)),
        ]);

        // add five OneHourElectricities for today, with kilowatts=7
        factory(OneHourElectricity::class, 5)->create([
            'panel_id' => $panelId,
            'kilowatts' => 7,
            'hour' => Carbon::today()->addHours(rand(2, 6)),
        ]);

        // add three OneHourElectricities for today, with kilowatts=11
        factory(OneHourElectricity::class, 3)->create([
            'panel_id' => $panelId,
            'kilowatts' => 11,
            'hour' => Carbon::today()->addHours(rand(2, 6)),
        ]);
    }
}
