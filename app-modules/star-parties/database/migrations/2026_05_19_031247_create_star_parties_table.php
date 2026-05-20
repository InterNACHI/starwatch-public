<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	public function up(): void
	{
		Schema::create('star_parties', function(Blueprint $table) {
			$table->id();
			$table->foreignId('lodge_id')->constrained()->cascadeOnDelete();
			$table->string('title');
			$table->string('location');
			$table->unsignedInteger('capacity');
			$table->dateTime('scheduled_at');
			$table->timestamps();
			$table->softDeletes();
			$table->index('scheduled_at');
		});
	}
	
	public function down(): void
	{
		Schema::dropIfExists('star_parties');
	}
};
