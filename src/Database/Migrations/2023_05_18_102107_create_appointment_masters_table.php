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
        Schema::create('appointment_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('appointment_setup_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('appointment_type_id')->unsigned();
            $table->date('appointment_date')->nullable()->default(null);
            $table->time('start_time')->nullable()->default(null);
            $table->time('end_time')->nullable()->default(null);
            $table->integer('available_slot')->default(0);
            $table->integer('used_slot')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('appointment_setup_id')
                ->references('id')->on('appointment_setups')
                ->onDelete('cascade');
            $table->foreign('appointment_type_id')
                ->references('id')->on('appointment_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_masters');
    }
};
