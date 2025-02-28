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
        Schema::create('tracers', function (Blueprint $table) {
            $table->string('id', 255)->unique(); // trace id
            $table->string('client', 255);
            $table->string('user_id', 255)->nullable();
            $table->string('cname', 200);
            $table->string('cid', 255);
            $table->string('type_of', 30);
            $table->timestamp('stamp');
            $table->text('request_id');
            $table->text('logs');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
