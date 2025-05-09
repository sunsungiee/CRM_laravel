<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("subject");
            $table->text("description");
            $table->date("date")->nullable();
            $table->time("time")->nullable();
            $table->unsignedBigInteger("contact_id")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();;
            $table->unsignedBigInteger("priority_id");
            $table->unsignedBigInteger("status_id")->default(1);


            $table->timestamps();
            $table->softDeletes();


            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("contact_id")->references("id")->on("contacts");
            $table->foreign("status_id")->references("id")->on("statuses");
            $table->foreign("priority_id")->references("id")->on("priorities");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
