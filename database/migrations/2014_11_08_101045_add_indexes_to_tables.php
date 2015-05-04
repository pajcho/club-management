<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\Schema;

    class AddIndexesToTables extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('date_history', function ($table) {
                $table->index('member_id');
            });
            Schema::table('member_group_data', function ($table) {
                $table->index('group_id');
                $table->index('member_id');
            });
            Schema::table('members', function ($table) {
                $table->index('group_id');
            });
            Schema::table('users_groups', function ($table) {
                $table->index('user_id');
            });
            Schema::table('users_groups_data', function ($table) {
                $table->index('user_id');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('date_history', function ($table) {
                $table->dropIndex('member_id');
            });
            Schema::table('member_group_data', function ($table) {
                $table->dropIndex('group_id');
                $table->dropIndex('member_id');
            });
            Schema::table('members', function ($table) {
                $table->dropIndex('group_id');
            });
            Schema::table('users_groups', function ($table) {
                $table->dropIndex('user_id');
            });
            Schema::table('users_groups_data', function ($table) {
                $table->dropIndex('user_id');
            });
        }

    }
