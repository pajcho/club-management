<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMemberGroupDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('member_group_data', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('member_id');
            $table->integer('year');
            $table->integer('month');
            $table->unsignedInteger('payed')->default(0);
            $table->text('attendance')->nullable()->default(NULL);

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
        Schema::drop('member_group_data');
	}

}
