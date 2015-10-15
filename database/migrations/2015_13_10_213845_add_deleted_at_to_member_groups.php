<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\Schema;

    class AddDeletedAtToMemberGroups extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('member_groups', function ($table) {
                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('member_groups', function ($table) {
                $table->dropSoftDeletes();
            });
        }

    }
