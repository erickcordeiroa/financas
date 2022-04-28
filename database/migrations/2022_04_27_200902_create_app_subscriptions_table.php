<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('plan_id')->constrained('app_plans');
            $table->foreignId('card_id')->constrained('app_cards_credit');
            $table->string('status')->default('active');
            $table->string('pay_status')->default('active');
            $table->date('started');
            $table->integer('due_day');
            $table->date('next_due');
            $table->date('last_charge');
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
        Schema::dropIfExists('app_subscriptions');
    }
}
