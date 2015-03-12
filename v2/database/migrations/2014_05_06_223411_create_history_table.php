<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateHistoryTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('history', function (Blueprint $table) {
                $table->increments('id');
                $table->string('historable_type');
                $table->integer('historable_id');
                $table->integer('user_id')->nullable();
                $table->text('message');

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
            Schema::drop('history');
        }

    }
