<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('donor_name');
            $table->string('donor_phone')->nullable();
            $table->string('donor_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->text('prayer_message')->nullable();
            $table->string('fund_book');
            $table->string('channel');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('manual_transfer');
            $table->string('payment_status')->default('pending');
            $table->string('proof_of_transfer')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['fund_book', 'payment_status']);
            $table->index(['branch_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
