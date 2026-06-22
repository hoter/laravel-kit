<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class UpdateProductStock implements ShouldQueue
{
    use Queueable;

    public $tries = 5;
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(public Product $product, public int $quantity)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->product->stock = $this->quantity;
        $this->product->save();
        Log::info("Update product ({$this->product->name}) quantity: {$this->quantity}");
    }
}
