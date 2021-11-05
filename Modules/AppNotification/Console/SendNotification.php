<?php

namespace Modules\AppNotification\Console;

use Illuminate\Console\Command;
use Modules\AppNotification\Entities\AutoSendNotification;
use Modules\AppNotification\Helpers\FirebaseApi;
use Modules\AppNotification\Helpers\FirebaseMessage;

class SendNotification extends Command
{
    protected $signature = 'app:send-notification';

    protected $description = 'Gửi thông báo';

    protected $expression = '* 2 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $rows = AutoSendNotification::where('status', '=', 2)
            ->limit(1)
            ->get();

        foreach ($rows as $row) {
            $row->update([
                'status' => 3,
            ]);

            try {
                $message = new FirebaseMessage($row->title, $row->message, $row->url, $row->image);
                $device_tokens = $row->getUserDeviceTokens();

                if (empty($device_tokens)) {
                    $row->update([
                        'status' => 0,
                        'error' => 'Cannot get device tokens',
                    ]);

                    continue;
                }

                $firebase = new FirebaseApi();
                $response = $firebase->send($device_tokens, $message);

                $this->info('success: '. $response->success);
                $this->info('failure: '. $response->failure);
                var_dump($response);
            }
            catch (\Exception $exception) {
                $row->update([
                    'status' => 0,
                    'error' => 'SendNotification Command: ' . $exception->getMessage(),
                ]);

                \Log::error('SendNotification Command: ' . $exception->getMessage());

                continue;
            }

            $row->update([
                'status' => 1,
            ]);
        }

    }
}
