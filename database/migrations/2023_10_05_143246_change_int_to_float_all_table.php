<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('global_settings', function (Blueprint $table) {
            $table->double('dealer')->change();
            $table->double('buyer')->change();
        });
        Schema::table('manual_settings', function (Blueprint $table) {
            $table->double('dealer')->change();
            $table->double('buyer')->change();
        });
        Schema::table('global_withdraw_settings', function (Blueprint $table) {
            $table->double('minimum_withdraw_amount')->change();
            $table->double('company_charge')->change();
        });
        Schema::table('withdraw_histories', function (Blueprint $table) {
            $table->double('company_charge')->change();
            $table->double('withdrawable_amount')->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->double('user_withdraw_amount')->change();
            $table->double('user_withdraw_charge')->change();
            $table->double('total_order_amount')->change();
            $table->double('total_order_repurchase_amount')->change();
            $table->double('seller_repurchase_transfer_amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->integer('dealer')->change();
            $table->integer('buyer')->change();
        });
        Schema::table('manual_settings', function (Blueprint $table) {
            $table->integer('dealer')->change();
            $table->integer('buyer')->change();
        });
        Schema::table('global_withdraw_settings', function (Blueprint $table) {
            $table->integer('minimum_withdraw_amount')->change();
            $table->integer('company_charge')->change();
        });
        Schema::table('withdraw_histories', function (Blueprint $table) {
            $table->float('company_charge')->change();
            $table->float('withdrawable_amount')->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->float('user_withdraw_amount')->change();
            $table->float('user_withdraw_charge')->change();
            $table->float('total_order_amount')->change();
            $table->float('total_order_repurchase_amount')->change();
            $table->float('seller_repurchase_transfer_amount')->change();
        });
    }
};
