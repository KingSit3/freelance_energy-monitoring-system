<?php

namespace App\Console\Commands;

use App\Models\ActivePower;
use App\Models\CurrentLoad;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MqttClient;

class GetActivePower extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:get-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get MQTT Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $result = [];

            $mqtt = new MqttClient(env("MQTT_HOST"), env("MQTT_PORT"), null);
            $mqtt->connect();
            $mqtt->subscribe('data/monitor/group3/eon1', function ($topic, $message) use ($mqtt, &$result) {
                echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
                $result = json_decode($message, true);

                Log::info($result);

                $mqtt->interrupt();
            }, 0);

            $mqtt->registerLoopEventHandler(function ($client, $elapsedTime) {
                if ($elapsedTime > 5) {
                    throw new Exception("Wait Time Exceed!");
                    $client->interrupt();
                }
            });

            $mqtt->loop(true);
            $mqtt->disconnect();

            DB::transaction(function () use ($result) {

                ActivePower::create([
                    "terminal_time" => $result["_terminalTime"] ? Carbon::parse($result["_terminalTime"])->format('Y-m-d H:i:s') : null,
                    "active_power_1" => $result["01P_tot"],
                    "active_power_2" => $result["02P_tot"],
                    "active_power_3" => $result["03P_tot"],
                    "active_power_4" => $result["04P_tot"],
                    "active_power_5" => $result["05P_tot"],
                    "active_power_6" => $result["06P_tot"],
                    "active_power_7" => $result["07P_tot"],
                    "active_power_8" => $result["08P_tot"],
                    "active_power_9" => $result["09P_tot"],
                    "active_power_10" => $result["10P_tot"],
                    "active_power_11" => $result["11P_tot"],
                ]);

                CurrentLoad::create([
                    "terminal_time" => $result["_terminalTime"] ? Carbon::parse($result["_terminalTime"])->format('Y-m-d H:i:s') : null,
                    "1_1" => $result["01I1"],
                    "1_2" => $result["01I2"],
                    "1_3" => $result["01I3"],
                    "2_1" => $result["02I1"],
                    "2_2" => $result["02I2"],
                    "2_3" => $result["02I3"],
                    "3_1" => $result["03I1"],
                    "3_2" => $result["03I2"],
                    "3_3" => $result["03I3"],
                    "4_1" => $result["04I1"],
                    "4_2" => $result["04I2"],
                    "4_3" => $result["04I3"],
                    "5_1" => $result["05I1"],
                    "5_2" => $result["05I2"],
                    "5_3" => $result["05I3"],
                    "6_1" => $result["06I1"],
                    "6_2" => $result["06I2"],
                    "6_3" => $result["06I3"],
                    "7_1" => $result["07I1"],
                    "7_2" => $result["07I2"],
                    "7_3" => $result["07I3"],
                    "8_1" => $result["08I1"],
                    "8_2" => $result["08I2"],
                    "8_3" => $result["08I3"],
                    "9_1" => $result["09I1"],
                    "9_2" => $result["09I2"],
                    "9_3" => $result["09I3"],
                    "10_1" => $result["10I1"],
                    "10_2" => $result["10I2"],
                    "10_3" => $result["10I3"],
                    "11_1" => $result["11I1"],
                    "11_2" => $result["11I2"],
                    "11_3" => $result["11I3"],
                ]);
            });
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            DB::rollBack();
            ActivePower::create([
                "terminal_time" =>  null,
            ]);
            CurrentLoad::create([
                "terminal_time" =>  null,
            ]);
        }
    }
}
