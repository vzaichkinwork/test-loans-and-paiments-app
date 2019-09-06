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

            $table->string('loan_number')->index();
            $table->string('payment_number');
            $table->double('amount', 16,2);
            $table->double('orig_amount', 16,2);
            $table->string('orig_currency', 3);
            $table->unsignedSmallInteger('status')->default(2)->comment('0 - not assigned, 1 - partially assigned, 2 - assigned');
            $table->unsignedSmallInteger('imported')->default(0);

            $table->index(['loan_number', 'status']);

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
