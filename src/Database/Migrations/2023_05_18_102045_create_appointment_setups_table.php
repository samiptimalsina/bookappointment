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
        Schema::create('appointment_setups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('appointment_type_id')->unsigned();
            $table->date('effective_from')->nullable()->default(null);
            $table->date('effective_to')->nullable()->default(null);
            $table->time('start_time')->nullable()->default(null);
            $table->time('end_time')->nullable()->default(null);
            $table->string('time_slot')->nullable()->default(null);
            $table->string('day')->nullable()->default(null);
            $table->integer('available_slot')->default(0);
            $table->boolean('is_active')->default(1);
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->integer('updated_by')->unsigned()->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('appointment_setups');
    }
};
