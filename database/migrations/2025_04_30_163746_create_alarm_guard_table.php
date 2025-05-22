<?php

use App\Models\Api\Alarm;
use App\Models\Api\Guard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alarm_guard', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Alarm::class);
            $table->foreignIdFor(Guard::class);
            $table->dateTime('date');
            $table->boolean('is_false_alarm')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarm_guard');
    }
};
