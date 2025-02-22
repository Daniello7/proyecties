<?php

use App\Models\InternalPerson;
use App\Models\Package;
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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['entry', 'exit'])->default('entry');
            $table->enum('agency', Package::AGENCIES);
            $table->integer('package_count')->default(0);
            $table->string('external_entity');
            $table->foreignIdFor(User::class, 'receiver_user_id');
            $table->foreignIdFor(User::class, 'deliver_user_id')->nullable();
            $table->foreignIdFor(InternalPerson::class);
            $table->string('retired_by')->nullable();
            $table->timestamp('entry_time');
            $table->timestamp('exit_time')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
