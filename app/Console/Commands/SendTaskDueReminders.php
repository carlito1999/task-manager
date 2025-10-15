<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Mail\TaskDueReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendTaskDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due date reminder emails for tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending task due reminders...');

        // Get tasks that are due today or overdue and not completed
        $dueTasks = Task::with(['assignedUser', 'project'])
            ->whereNotNull('assigned_to')
            ->whereNotNull('due_date')
            ->where('status', '!=', 'done')
            ->where(function ($query) {
                $query->whereDate('due_date', '<=', Carbon::today())
                      ->orWhereDate('due_date', Carbon::tomorrow()); // Also send for tomorrow
            })
            ->get();

        $emailsSent = 0;

        foreach ($dueTasks as $task) {
            if ($task->assignedUser && $task->assignedUser->email) {
                try {
                    Mail::to($task->assignedUser)->send(new TaskDueReminder($task));
                    $emailsSent++;
                    
                    $this->line("✓ Reminder sent to {$task->assignedUser->name} for task: {$task->title}");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to send reminder to {$task->assignedUser->name} for task: {$task->title}");
                    $this->error("Error: " . $e->getMessage());
                }
            }
        }

        $this->info("Task due reminders completed. {$emailsSent} emails sent.");
        
        return 0;
    }
}
