<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusableTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('statusable', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('entity_id')->nullable();
			$table->string('entity_type')->nullable();
			$table->integer('status_id')->nullable();
			$table->dateTime('ended_at')->nullable();
			$table->nullableTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('statusable');
	}

}
