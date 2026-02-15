<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ...
public function up(): void
{
    Schema::table('provider_profiles', function (Blueprint $table) {
        $table->float('average_rating', 8, 2)->default(0.0)->after('experience_years');
        $table->unsignedInteger('review_count')->default(0)->after('average_rating');
    });
}

public function down(): void
{
    Schema::table('provider_profiles', function (Blueprint $table) {
        $table->dropColumn(['average_rating', 'review_count']);
    });
}
// ...
};
