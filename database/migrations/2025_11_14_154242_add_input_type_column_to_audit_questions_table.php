<?php

use App\Enums\InputType;
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
        Schema::table('audit_questions', function (Blueprint $table) {
            $table->unsignedTinyInteger('input_type')->default(InputType::TEXT);
            $table->text('inputs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_questions', function (Blueprint $table) {
            $table->dropColumn('input_type');
            $table->dropColumn('inputs');
        });
    }
};
