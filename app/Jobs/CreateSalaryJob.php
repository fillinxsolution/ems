<?php

namespace App\Jobs;

use App\Models\ImportCsv;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSalaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csv_id;
    /**
     * Create a new job instance.
     */
    public function __construct($csv_id)
    {
        $this->csv_id = $csv_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $csv = ImportCsv::with('imports.user')->findOrFail($this->csv_id);

        foreach ($csv->imports as $import) {


        }
    }
}
