<?php

use App\Enum\TicketPriority;
use App\Enum\TicketStatus;
use App\Service\ChatService;
use App\Service\TicketService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Command: php artisan migrate --path=database/migrations/2025_05_14_154348_create_tickets_table.php
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('subject');
            $table->text('description');
            $table->string('category');
            $table->enum('priority', TicketService::$priority)->default(TicketPriority::LOW);
            $table->enum('status', TicketService::$status)->default(TicketStatus::OPEN);
            $table->string('attachment')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
