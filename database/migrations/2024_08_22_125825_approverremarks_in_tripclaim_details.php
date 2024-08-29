<?php

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
        
        Schema::table('myg_09_trip_claim_details', function (Blueprint $table) {
            $table->string('approver_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('myg_09_trip_claim_details', function (Blueprint $table) {
            // Add an ENUM column for Status
            $table->dropColumn('approver_remarks');
        });
    }
};
