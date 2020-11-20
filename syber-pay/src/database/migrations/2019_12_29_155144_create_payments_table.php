<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('transaction_id')->unique();
            $table->bigInteger('order_id')->nullable()->references('id')->on('orders');

        //$table->string('operation_id')->unique();///the operation id
            $table->double('amount');
            
            $table->integer('customer_id')->nullable()->references('id')->on('customers');
            
            $table->string('hash')->nullable();
            $table->string('note')->nullable();
            
            ///

            $table->string('payment_method');
            ///0=cash 1=syber  2=wallet or online payment
            $table->integer('status');
            ///0=notvalid, 1=done
           
            
            $table->string('type')->default('1');
            ///1=order payment
            
            

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
        Schema::dropIfExists('payments');
    }
}
