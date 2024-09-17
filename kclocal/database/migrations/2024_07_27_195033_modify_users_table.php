<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 添加新字段
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable();
            }
            if (!Schema::hasColumn('users', 'dateOfBirth')) {
                $table->date('dateOfBirth')->nullable();
            }
            if (!Schema::hasColumn('users', 'contactNumber')) {
                $table->string('contactNumber')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile')) {
                $table->string('profile')->nullable();
            }
            if (!Schema::hasColumn('users', 'feedback')) {
                $table->string('feedback')->nullable();
            }
            if (!Schema::hasColumn('users', 'point')) {
                $table->unsignedInteger('point')->nullable();
            }
            if (!Schema::hasColumn('users', 'registeredAt')) {
                $table->timestamp('registeredAt')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            }
            if (!Schema::hasColumn('users', 'updatedAt')) {
                $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // 移除新添加的字段
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('users', 'dateOfBirth')) {
                $table->dropColumn('dateOfBirth');
            }
            if (Schema::hasColumn('users', 'contactNumber')) {
                $table->dropColumn('contactNumber');
            }
            if (Schema::hasColumn('users', 'profile')) {
                $table->dropColumn('profile');
            }
            if (Schema::hasColumn('users', 'feedback')) {
                $table->dropColumn('feedback');
            }
            if (Schema::hasColumn('users', 'point')) {
                $table->dropColumn('point');
            }
            if (Schema::hasColumn('users', 'registeredAt')) {
                $table->dropColumn('registeredAt');
            }
            if (Schema::hasColumn('users', 'updatedAt')) {
                $table->dropColumn('updatedAt');
            }
        });
    }
}
