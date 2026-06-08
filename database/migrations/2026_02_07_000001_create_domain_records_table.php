<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Ium\Domain\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::create('ium_domain_records', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->json('extra_custom')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ium_domain_records');
    }
};
