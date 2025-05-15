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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string("subject")->default("Новая сделка");
            $table->date("end_date")->nullable();
            $table->time("end_time")->nullable();
            $table->unsignedBigInteger("sum");

            $table->unsignedBigInteger("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("contact_id")->references("id")->on("contacts");;
            $table->unsignedBigInteger("phase_id")->references("id")->on("phases")->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
};
