<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->string('prediction')->nullable()->after('timestamp'); // أضف عمود النتيجة
        });
    }

    public function down()
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn('prediction');
        });
    }
};
