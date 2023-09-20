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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('reset_token')->nullable();
            $table->enum('type', [1, 2, 3, 4])->nullable()->comment('1=Admin,2=Seller/Dealer,3=Customer,4=Employee');
            $table->string('ref_code')->nullable();
            $table->string('ref_by')->nullable();
            $table->double('repurchase_amount')->default(0);
            $table->double('withdraw_amount')->default(0);
            $table->double('total_amount')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
