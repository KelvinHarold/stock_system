<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->boolean('is_closed')->default(false)->after('discount');
            $table->timestamp('closed_at')->nullable()->after('is_closed');
        });
    }

    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn(['is_closed', 'closed_at']);
        });
    }
};

