<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->timestamp('event_start_date')->nullable(false);
            $table->timestamp('event_end_date')->nullable(true);
            $table->text('description')->nullable(true);
            $table->text('address')->nullable(false);
            $table->smallInteger('member_limit')->nullable(false);
            $table->smallInteger('status');
            $table->text('image')->nullable(true);
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
        Schema::dropIfExists('event_lists');
    }
}
