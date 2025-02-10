<?php

namespace App\Console\Commands;

use DB;

use Carbon\Carbon;
use App\Models\Event;

use Spatie\WebhookServer\WebhookCall;

use Illuminate\Console\Command;

class BotTimetableUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:timetable-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send timetable updates to the discord bot';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (config('app.discord_bot_url') != '' ) {

            $nextEvent = Event::where('end', '>=', \Carbon\Carbon::now())
                ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first();

            if ($nextEvent && strtolower($nextEvent->status) == "published" && $nextEvent->discord_link_enabled && (Carbon::parse($nextEvent->end)->isFuture() && Carbon::parse($nextEvent->start)->subMinutes(60)->isPast())) {
                foreach ($nextEvent->timetables as $timetable) {
                    if (strtolower($timetable->status) == "published") {
                        $nextTimetableItems = $timetable->data()->where('start_time', '>=', Carbon::now())
                        ->get();

                        foreach ($nextTimetableItems as $nextTimetableItem) {
                            // 60 Minutes
                            if ($this->isWithinThresholdToSendMessage($nextTimetableItem->start_time, 59, 60)) {
                                $this->sendMessageToBot($nextEvent->discord_channel_id, "TIMETABLE: 1 HOUR WARNING: " . $nextTimetableItem->name);
                            }

                            // 30 Minutes
                            if ($this->isWithinThresholdToSendMessage($nextTimetableItem->start_time, 29, 30)) {
                                $this->sendMessageToBot($nextEvent->discord_channel_id, "TIMETABLE: 30 Minute WARNING: " . $nextTimetableItem->name);
                            }

                            // 15 Minutes
                            if ($this->isWithinThresholdToSendMessage($nextTimetableItem->start_time, 14, 15)) {
                                $this->sendMessageToBot($nextEvent->discord_channel_id, "TIMETABLE: 15 Minute WARNING: " . $nextTimetableItem->name);
                            }

                            // 5 Minutes
                            if ($this->isWithinThresholdToSendMessage($nextTimetableItem->start_time, 4, 5)) {
                                $this->sendMessageToBot($nextEvent->discord_channel_id, "TIMETABLE: 5 Minute WARNING: " . $nextTimetableItem->name);
                            }

                            // Starting Now
                            if ($this->isWithinThresholdToSendMessage($nextTimetableItem->start_time, -1, 0)) {
                                $this->sendMessageToBot($nextEvent->discord_channel_id, "TIMETABLE: STARTING SOON: " . $nextTimetableItem->name);
                            }
                        }
                    }
                }

            }

        }

        return Command::SUCCESS;
    }
    
    private function sendMessageToBot($channelID, $message) {
        WebhookCall::create()
        ->url(config('app.discord_bot_url') . '/message/channel')
        ->payload([
            'channel_id' => $channelID,
            'message' => $message,
        ])
        ->maximumTries(5)
        ->useSecret(config('app.discord_bot_secret'))
        ->dispatch();
    }

    private function isWithinThresholdToSendMessage($time, $min, $max) {
        $minThreshold = Carbon::now()->addMinutes($min);
        $maxThreshold = Carbon::now()->addMinutes($max);
        return Carbon::parse($time)->between($minThreshold, $maxThreshold);
    }
}
