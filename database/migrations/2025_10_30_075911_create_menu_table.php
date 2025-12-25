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
        Schema::create('s_menu', function (Blueprint $table) {
            $table->id();
            $table->string('idmenu')->nullable();
            $table->integer('status')->default(0);
            $table->string('kode')->nullable();
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->integer('urutan')->nullable();
            $table->string('viewparent')->nullable();
            $table->string('mainapiroute')->nullable();
            $table->string('mainapicontroller')->nullable();
            $table->string('mainroute')->nullable();
            $table->string('mainjsroute')->nullable();
            $table->string('mainjs')->nullable();
            $table->integer('tingkatapprove')->default(0)->nullable();
            $table->integer('jenis')->default(1)->comment('1: transaksi by menu, 2: transaksi by user, 3: transaksi by jenis');
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
        Schema::dropIfExists('s_menu');
    }
};
