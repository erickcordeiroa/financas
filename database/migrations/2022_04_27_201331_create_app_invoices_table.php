<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('wallet_id')->constrained('app_wallets');
            $table->foreignId('category_id')->constrained('app_categories');
            $table->integer('invoice_of');
            $table->string('description');
            $table->string('type');
            $table->decimal('value', 10, 2);
            $table->string('currency')->default('BRL');
            $table->date('due_at');
            $table->string('repeat_when');
            $table->string('period')->default('month');
            $table->integer('enrollments');
            $table->integer('enrollments_of');
            $table->string('status')->default('unpaid');
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
        Schema::dropIfExists('app_invoices');
    }
}
