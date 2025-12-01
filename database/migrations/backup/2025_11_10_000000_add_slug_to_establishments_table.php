<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->unique('slug');
        });

        DB::transaction(function () {
            $establishments = DB::table('establishments')
                ->select('id', 'name')
                ->get();

            foreach ($establishments as $establishment) {
                $baseSlug = Str::slug($establishment->name);

                if ($baseSlug === '') {
                    $baseSlug = 'establishment-' . $establishment->id;
                }

                $slug = $baseSlug;
                $suffix = 1;

                while (
                    DB::table('establishments')
                        ->where('slug', $slug)
                        ->where('id', '!=', $establishment->id)
                        ->exists()
                ) {
                    $slug = "{$baseSlug}-{$suffix}";
                    $suffix++;
                }

                DB::table('establishments')
                    ->where('id', $establishment->id)
                    ->update(['slug' => $slug]);
            }
        });

        Schema::table('establishments', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};



