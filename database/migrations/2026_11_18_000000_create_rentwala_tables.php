<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role')->default('owner');
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('phone')->nullable();
            $t->string('business_name')->nullable();
            $t->string('business_type')->nullable();      // hostel, house, building
            $t->boolean('onboarded')->default(false);
            $t->boolean('is_active')->default(true);
        });
        // a "space" = a hostel room, a house, or a flat/shop. beds = how many people it holds.
        Schema::create('spaces', function (Blueprint $t) {
            $t->id();
            $t->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $t->string('name');
            $t->unsignedInteger('beds')->default(1);
            $t->unsignedInteger('rent')->default(0);
            $t->timestamps();
        });
        Schema::create('occupants', function (Blueprint $t) {
            $t->id();
            $t->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('space_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('phone');
            $t->string('cnic')->nullable();
            $t->string('photo')->nullable();
            $t->unsignedInteger('rent');
            $t->unsignedTinyInteger('due_day')->default(1);
            $t->date('joined_on');
            $t->date('left_on')->nullable();
            $t->string('status')->default('active');
            $t->timestamps();
        });
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('occupant_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('amount');
            $t->date('for_month');
            $t->date('paid_on');
            $t->string('method')->default('cash');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['payments', 'occupants', 'spaces'] as $x) {
            Schema::dropIfExists($x);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropConstrainedForeignId('owner_id');
            $t->dropColumn(['role', 'phone', 'business_name', 'business_type', 'onboarded', 'is_active']);
        });
    }
};
