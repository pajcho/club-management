<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('members', function(Blueprint $table) {
			$table->increments('id');
			$table->string('uid');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('phone');
			$table->text('notes');
			$table->dateTime('dob');
			$table->dateTime('dos');
			$table->dateTime('doc')->nullable()->default(NULL);
			$table->unsignedInteger('active')->default(1);

			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('members');
	}

}
