<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->text('shipping_address')->nullable()->after('gender');
            $table->text('billing_address')->nullable()->after('shipping_address');
            $table->string('preferred_payment_method')->nullable()->after('billing_address');
            $table->boolean('newsletter_subscription')->default(false)->after('preferred_payment_method');
            $table->decimal('total_spent', 10, 2)->default(0)->after('newsletter_subscription');
            $table->integer('orders_count')->default(0)->after('total_spent');
            $table->timestamp('last_order_at')->nullable()->after('orders_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'birth_date',
                'gender',
                'shipping_address',
                'billing_address',
                'preferred_payment_method',
                'newsletter_subscription',
                'total_spent',
                'orders_count',
                'last_order_at'
            ]);
        });
    }
}; 