<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Ium\Pos\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::create($this->prefix . 'terminals', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->morphs('terminalable');
            $table->enum('status', ['active', 'inactive', 'offline'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'terminals');
    }
};
