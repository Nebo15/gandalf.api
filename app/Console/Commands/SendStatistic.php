<?php
namespace App\Console\Commands;

use App\Models\Decision;
use MongoDB\BSON\UTCDatetime;
use Illuminate\Console\Command;

class SendStatistic extends Command
{
    protected $signature = 'send:statistic';

    protected $description = 'Send statistic of usage to CachetHQ service';

    public function handle()
    {
        $date = (new \DateTime('now'));

        $countDecisionsPerMinute = Decision::where([
            'created_at' => [
                '$lt' => new UTCDatetime($date->getTimestamp() * 1000),
                '$gte' => new UTCDatetime($date->modify('-1 minute')->getTimestamp() * 1000),

            ],
        ])->count();
        $data = json_encode([
            'value' => $countDecisionsPerMinute,
            'updated_at' => $date->format('Y-m-d H:i:s'),
            'id' => $date->getTimestamp(),
        ]);

        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'X-Cachet-Token: ' . config('services.status.access_token'),
            'Content-Length: ' . strlen($data),
        ];
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, config('services.status.decisions_per_minute_link'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
