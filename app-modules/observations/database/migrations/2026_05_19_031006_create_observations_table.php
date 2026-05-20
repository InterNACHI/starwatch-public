<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	public function up(): void
	{
		Schema::create('observations', function(Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->foreignId('lodge_id')->nullable()->constrained()->nullOnDelete();
			$table->string('target');
			$table->text('notes')->nullable();
			$table->dateTime('observed_at');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['user_id', 'observed_at']);
		});
	}
	
	public function down(): void
	{
		Schema::dropIfExists('observations');
	}
};
