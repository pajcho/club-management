<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\Schema;

    class UpdateUsersTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name', 255)->after('id')->nullable();
                $table->string('last_name', 255)->after('first_name')->nullable();
                $table->string('type', 255)->after('password')->default('admin'); // For now it can have values: admin or trainer
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('first_name', 'last_name', 'type');
            });
        }

    }
