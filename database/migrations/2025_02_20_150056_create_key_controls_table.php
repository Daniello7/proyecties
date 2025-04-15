<?php

use App\Models\Key;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('key_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Key::class);
            $table->foreignIdFor(Person::class);
            $table->foreignIdFor(User::class, 'deliver_user_id');
            $table->foreignIdFor(User::class, 'receiver_user_id')->nullable()->default(null);
            $table->string('comment')->nullable();
            $table->timestamp('exit_time');
            $table->timestamp('entry_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_controls');
    }
};
