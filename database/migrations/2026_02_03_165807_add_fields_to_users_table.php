<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('hiring_date')->nullable()->after('email');
            $table->string('dui')->unique()->nullable()->after('hiring_date');
            $table->string('phone_number')->nullable()->after('dui');
            $table->date('birth_date')->nullable()->after('phone_number');
            $table->softDeletes()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('hiring_date');
            $table->dropColumn('dui');
            $table->dropColumn('phone_number');
            $table->dropColumn('birth_date');
            $table->dropSoftDeletes();
        });
    }
};
