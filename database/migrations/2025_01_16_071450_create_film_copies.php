<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmCopies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('film_copies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('actors')->nullable();
            $table->string('banners')->nullable();
            $table->string('producers')->nullable();
            $table->string('country')->nullable();
            $table->string('genre')->nullable();
            $table->string('duration');
            $table->string('url');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('film_stills')->nullable();
            $table->integer('ticket_soft_id');
            $table->date('disabled');
            $table->string('age');
            $table->text('full_age');
            $table->date('date_description')->nullable();
            $table->boolean('update_description')->nullable();
            $table->text('description')->nullable();
            $table->date('date_poster')->nullable();
            $table->boolean('update_poster')->nullable();
            $table->boolean('publication')->default(false);
            $table->boolean('ps')->default(false);
            $table->boolean('retro')->default(false);
            $table->boolean('not_only_jazz')->default(false);
            $table->integer('external_film_copy_id');
            $table->string('rating')->default("");
            $table->string('video')->nullable();
            $table->string('rating_world')->nullable();
            $table->string('rating_ex')->nullable();
            $table->date('memorandum')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('film_copies');
    }
}
