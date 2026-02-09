<?php

namespace App\Jobs;

use App\Models\DailyStatistic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateDailyStats implements ShouldQueue
{
    use Queueable , Dispatchable, InteractsWithQueue ,SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $column
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = now()->toDateString();

        DailyStatistic::updateOrInsert(
            ['date' => $date],
            ['column' => DB::raw('column + 1')]
        );
    }
}
