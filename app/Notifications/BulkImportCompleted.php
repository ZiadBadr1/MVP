<?php

namespace App\Notifications;

use App\Models\BulkUserImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BulkImportCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BulkUserImport $bulk)
    {}

    /**
     * Channels: Mail + Database
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Email version
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Bulk Import Completed")
            ->greeting("Hello $notifiable->name,")
            ->line("Your bulk import #{$this->bulk->id} has finished.")
            ->line("Total Records: {$this->bulk->total_count}")
            ->line("Success: {$this->bulk->success_count}")
            ->line("Failed: {$this->bulk->failed_count}")
            ->line("You can view the errors in your dashboard if any.");
    }

    /**
     * Database version
     */
    public function toArray(): array
    {
        return [
            'bulk_id' => $this->bulk->id,
            'status' => $this->bulk->status,
            'total_records' => $this->bulk->total_count,
            'success_count' => $this->bulk->success_count,
            'failed_count' => $this->bulk->failed_count,
        ];
    }
}
