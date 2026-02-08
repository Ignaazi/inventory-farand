<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('sparepart_requests', function (Blueprint $table) {
        $table->id();
        $table->string('nik');
        $table->string('nama');
        // Relasi ke tabel spareparts (hanya jenisnya)
        $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
        $table->integer('qty');
        $table->text('remark')->nullable();
        $table->enum('type', ['in', 'out']); // Membedakan menu In atau Out
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->foreignId('user_id')->constrained('users'); // Siapa yang menginput/meminta
        $table->timestamp('approved_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_requests');
    }
};
