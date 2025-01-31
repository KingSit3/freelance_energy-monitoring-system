<?php

namespace App\Console\Commands;

use App\Models\ActivePower;
use App\Models\CurrentLoad;
use App\Models\Dpm;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MqttClient;

class GetMqttData extends Command
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
            $mqtt->subscribe(env("MQTT_TOPIC", "data/monitor/group3/eon1"), function ($topic, $message) use ($mqtt, &$result) {
                echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
                $result = json_decode($message, true);

                $mqtt->interrupt();
            }, 0);

            $mqtt->registerLoopEventHandler(function ($client, $elapsedTime) {
                if ($elapsedTime > 60) {
                    $client->interrupt();
                    throw new Exception("Wait Time Exceed!");
                }
            });

            $mqtt->loop(true);
            $mqtt->disconnect();

            Dpm::create([
                "payload" => $result,
            ]);

            // DB::transaction(function () use ($result) {


            //     ActivePower::create([
            //         "terminal_time" => $result["_terminalTime"] ? Carbon::parse($result["_terminalTime"])->format('Y-m-d H:i:s') : null,
            //         "active_power_1" => abs($result["01kWH"]),
            //         "active_power_2" => abs($result["02kWH"]),
            //         "active_power_3" => abs($result["03kWH"]),
            //         "active_power_4" => abs($result["04kWH"]),
            //         "active_power_5" => abs($result["05kWH"]),
            //         "active_power_6" => abs($result["06kWH"]),
            //         "active_power_7" => abs($result["07kWH"]),
            //         "active_power_8" => abs($result["08kWH"]),
            //         "active_power_9" => abs($result["09kWH"]),
            //         "active_power_10" => abs($result["10kWH"]),
            //         "active_power_11" => abs($result["11kWH"]),
            //     ]);

            //     CurrentLoad::create([
            //         "terminal_time" => $result["_terminalTime"] ? Carbon::parse($result["_terminalTime"])->format('Y-m-d H:i:s') : null,
            //         "1_1" => abs($result["01I1"]),
            //         "1_2" => abs($result["01I2"]),
            //         "1_3" => abs($result["01I3"]),
            //         "2_1" => abs($result["02I1"]),
            //         "2_2" => abs($result["02I2"]),
            //         "2_3" => abs($result["02I3"]),
            //         "3_1" => abs($result["03I1"]),
            //         "3_2" => abs($result["03I2"]),
            //         "3_3" => abs($result["03I3"]),
            //         "4_1" => abs($result["04I1"]),
            //         "4_2" => abs($result["04I2"]),
            //         "4_3" => abs($result["04I3"]),
            //         "5_1" => abs($result["05I1"]),
            //         "5_2" => abs($result["05I2"]),
            //         "5_3" => abs($result["05I3"]),
            //         "6_1" => abs($result["06I1"]),
            //         "6_2" => abs($result["06I2"]),
            //         "6_3" => abs($result["06I3"]),
            //         "7_1" => abs($result["07I1"]),
            //         "7_2" => abs($result["07I2"]),
            //         "7_3" => abs($result["07I3"]),
            //         "8_1" => abs($result["08I1"]),
            //         "8_2" => abs($result["08I2"]),
            //         "8_3" => abs($result["08I3"]),
            //         "9_1" => abs($result["09I1"]),
            //         "9_2" => abs($result["09I2"]),
            //         "9_3" => abs($result["09I3"]),
            //         "10_1" => abs($result["10I1"]),
            //         "10_2" => abs($result["10I2"]),
            //         "10_3" => abs($result["10I3"]),
            //         "11_1" => abs($result["11I1"]),
            //         "11_2" => abs($result["11I2"]),
            //         "11_3" => abs($result["11I3"]),
            //     ]);
            // });
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Dpm::create([
                "payload" => null,
            ]);

            // DB::rollBack();
            // ActivePower::create([
            //     "terminal_time" =>  null,
            // ]);
            // CurrentLoad::create([
            //     "terminal_time" =>  null,
            // ]);
        }
    }
}
