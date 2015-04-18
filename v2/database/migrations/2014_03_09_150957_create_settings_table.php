<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\Schema;

    class CreateSettingsTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('key');
                $table->string('value');
                $table->text('description')->nullable()->default(null);
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::drop('settings');
        }

    }
