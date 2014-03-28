<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMemberGroupDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('member_group_details', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('year');
            $table->integer('month');
            $table->text('details')->nullable()->default(NULL);

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
        Schema::drop('member_group_details');
	}

}
