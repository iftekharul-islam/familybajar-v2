<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nominee_name')->after('image')->nullable();
            $table->string('nominee_relation')->after('nominee_name')->nullable();
            $table->string('nominee_nid')->after('nominee_relation')->nullable();
            $table->integer('package')->after('nominee_nid')->nullable()->comment('1=Level-1, 2=Level-2, 3=Level-3, 4=Level-4, 5=Level-5');
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
            $table->dropColumn('nominee_name');
            $table->dropColumn('nominee_relation');
            $table->dropColumn('nominee_nid');
            $table->dropColumn('package');
        });
    }
};
