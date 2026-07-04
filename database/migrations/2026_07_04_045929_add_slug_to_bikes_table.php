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
        Schema::table('bikes', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        DB::table('bikes')->whereNull('slug')->get()->each(function ($bike) {
            $base = str($bike->name)->slug()->toString();
            $slug = $base;
            $i = 1;
            while (DB::table('bikes')->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('bikes')->where('id', $bike->id)->update(['slug' => $slug]);
        });

        Schema::table('bikes', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
