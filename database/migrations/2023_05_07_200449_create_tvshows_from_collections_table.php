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
        Schema::create('tvshows__collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->unsignedBigInteger('tvshow_id')->unique();
            $table->integer('watched_episodes')->unsigned();
            $table->string('status');
            $table->string('score');
            $table->date('created_at');
            $table->timestamp('updated_at');

            $table->foreign('collection_id')->references('id')->on('collections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvshows__collections');
    }
};
