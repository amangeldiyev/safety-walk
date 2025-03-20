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
        Schema::table('audits', function (Blueprint $table) {
            $table->boolean('good_practice')->default(false);
            $table->boolean('point_of_improvement')->default(false);
            $table->string('signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn('good_practice');
            $table->dropColumn('point_of_improvement');
            $table->dropColumn('signature');
        });
    }
};
