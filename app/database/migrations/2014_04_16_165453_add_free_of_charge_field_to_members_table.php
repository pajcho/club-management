<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddFreeOfChargeFieldToMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('members', function(Blueprint $table) {
            $table->unsignedInteger('freeOfCharge')->after('active')->default(0);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('members', function(Blueprint $table) {
            $table->dropColumn('freeOfCharge');
        });
	}

}
