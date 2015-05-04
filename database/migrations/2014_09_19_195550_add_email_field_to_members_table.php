<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\Schema;

    class AddEmailFieldToMembersTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('members', function (Blueprint $table) {
                $table->string('email')->after('last_name')->nullable()->default(null);
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

    }
