<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('appointment_master_id')->unsigned();
            $table->bigInteger('appointment_setup_id')->unsigned();
            $table->string('unique_id')->nullable();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->integer('city')->nullable();
            $table->integer('state')->nullable();
            $table->integer('zip')->nullable();
            $table->string('address')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('assign_to')->unsigned()->nullable()->default(null);
            $table->boolean('is_confirmed')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_notified');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('appointment_master_id')
                ->references('id')->on('appointment_masters')
                ->onDelete('cascade');

            $table->foreign('appointment_setup_id')
                ->references('id')->on('appointment_setups')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
