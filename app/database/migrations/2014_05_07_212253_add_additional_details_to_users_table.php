<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddAdditionalDetailsToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function(Blueprint $table) {
            $table->string('phone', 255)->after('email')->nullable()->default(null);
            $table->string('address', 255)->after('email')->nullable()->default(null);
            $table->text('notes')->after('email')->nullable()->default(null);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('phone', 'address', 'notes');
        });
	}

}
