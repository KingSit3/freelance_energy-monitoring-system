<?php

namespace App\Console\Commands;

use App\Models\ActivePower;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MqttClient;

class GetActivePower extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:active-power';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get new Active Power data';

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
                $mqtt->interrupt();
            }, 0);

            $mqtt->loop(true);
            $mqtt->disconnect();

            ActivePower::create([
                "terminal_time" => $result["_terminalTime"] ? Carbon::parse($result["_terminalTime"])->format('Y-m-d H:i:s') : null,
                "active_power_1" => $result["ActivePower1"] ?? null,
                "active_power_2" => $result["ActivePower2"] ?? null,
                "active_power_3" => $result["ActivePower3"] ?? null,
                "active_power_4" => $result["ActivePower4"] ?? null,
                "active_power_5" => $result["ActivePower5"] ?? null,
                "active_power_6" => $result["ActivePower6"] ?? null,
                "active_power_7" => $result["ActivePower7"] ?? null,
                "active_power_8" => $result["ActivePower8"] ?? null,
                "active_power_9" => $result["ActivePower9"] ?? null,
                "active_power_10" => $result["ActivePower10"] ?? null,
                "active_power_11" => $result["ActivePower11"] ?? null,
                "active_power_12" => $result["ActivePower12"] ?? null,
                "active_power_13" => $result["ActivePower13"] ?? null,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            ActivePower::create([
                "terminal_time" =>  null,
                "active_power_1" => null,
                "active_power_2" => null,
                "active_power_3" => null,
                "active_power_4" => null,
                "active_power_5" => null,
                "active_power_6" => null,
                "active_power_7" => null,
                "active_power_8" => null,
                "active_power_9" => null,
                "active_power_10" => null,
                "active_power_11" => null,
                "active_power_12" => null,
                "active_power_13" => null,
            ]);
        }
    }
}
