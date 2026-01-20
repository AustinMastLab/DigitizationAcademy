<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->renameColumn('front_image', 'page_image');
            $table->renameColumn('back_image', 'tile_image');
            $table->enum('type', ['2 Hour', '12 Hour'])->default('2 Hour')->after('title');
            $table->string('slug')->after('title')->nullable();
            $table->renameColumn('objectives', 'description');
            $table->text('objectives')->after('description')->nullable();
            $table->string('instructor')->after('language');
            $table->integer('sort_order')->after('active')->default(0);
            $table->string('video')->after('tile_image')->nullable();
            $table->string('syllabus')->after('tile_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->renameColumn('page_image', 'front_image');
            $table->renameColumn('tile_image', 'back_image');
            $table->dropColumn(['type', 'slug']);
            $table->renameColumn('description', 'objectives');
            $table->dropColumn(['objectives', 'instructor', 'sort_order', 'video', 'syllabus']);
        });
    }
};
