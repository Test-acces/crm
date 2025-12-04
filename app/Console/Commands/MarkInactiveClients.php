<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Activity;
use Illuminate\Console\Command;

class MarkInactiveClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:mark-inactive {--days=30 : Number of days without activity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark clients as inactive if they have no activity for the specified number of days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("Checking for clients inactive for {$days} days...");

        // Get clients that are active and have no activities in the last X days
        $inactiveClients = Client::where('status', 'active')
            ->whereDoesntHave('activities', function ($query) use ($days) {
                $query->where('date', '>=', now()->subDays($days));
            })
            ->get();

        $count = $inactiveClients->count();

        if ($count === 0) {
            $this->info('No clients to mark as inactive.');
            return;
        }

        $this->info("Found {$count} clients to mark as inactive.");

        foreach ($inactiveClients as $client) {
            $client->update(['status' => 'inactive']);
            $this->line("Marked client '{$client->name}' as inactive.");
        }

        $this->info('Done.');
    }
}