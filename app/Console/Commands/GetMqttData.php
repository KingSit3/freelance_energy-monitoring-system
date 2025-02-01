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
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Dpm::create([
                "payload" => null,
            ]);
        }
    }
}
