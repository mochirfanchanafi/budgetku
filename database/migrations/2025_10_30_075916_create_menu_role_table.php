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
        Schema::create('s_menu_role', function (Blueprint $table) {
            $table->id();
            $table->string('idmenu')->nullable();
            $table->string('idrole')->nullable();
            $table->integer('is_create')->nullable()->default(0);
            $table->integer('is_read')->nullable()->default(0);
            $table->integer('is_update')->nullable()->default(0);
            $table->integer('is_delete')->nullable()->default(0);
            $table->integer('is_reset')->nullable()->default(0);
            $table->integer('is_reject')->nullable()->default(0);
            $table->integer('is_close')->nullable()->default(0);
            $table->integer('is_admin')->nullable()->default(0);
            $table->integer('tingkatapprove')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_menu_role');
    }
};
