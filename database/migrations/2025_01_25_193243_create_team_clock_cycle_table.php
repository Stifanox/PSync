<?php

use App\Models\Team;
use App\Models\TeamConfiguration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_clock_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TeamConfiguration::class,'team_configuration_id');
            $table->foreignIdFor(Team::class,'team_id');
            $table->timestamp('start_time');
            $table->timestamp('end_work_time');
            $table->timestamp('end_free_time');
            $table->string('clock_state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_clock_cycles');
    }
};
