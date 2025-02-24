<?php

use App\Models\InternalPerson;
use App\Models\Person;
use App\Models\PersonEntry;
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
        Schema::create('person_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Person::class);
            $table->foreignIdFor(InternalPerson::class);
            $table->string('comment')->nullable();
            $table->enum('reason', PersonEntry::REASONS);
            $table->timestamp('arrival_time');
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_entries');
    }
};
