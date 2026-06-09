<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Ium\Pos\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::create($this->prefix . 'receipts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('session_id')->constrained($this->prefix . 'sessions')->cascadeOnDelete();
            $table->morphs('orderable');
            $table->decimal('total_amount', 15, 2);
            $table->string('payment_method');
            $table->string('reference_no')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'receipts');
    }
};
