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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->nullable()->constrained();

            $table->unsignedBigInteger('legacy_id')->nullable(true);

            $table->string('name')->nullable();
            $table->string('name_latin')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('location')->nullable();

            $table->unsignedInteger('age')->nullable();
            $table->unsignedInteger('year_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->float('measurement_size')->nullable();

            $table->string('position')->nullable();
            $table->string('preferences')->nullable();

            $table->boolean('has_face_photo')->default(false);
            $table->boolean('has_body_photo')->default(false);
            $table->boolean('has_dick_photo')->default(false);
            $table->boolean('has_anus_photo')->default(false);
            $table->boolean('has_cum_photo')->default(false);
            $table->boolean('has_had_sex')->default(false);

            $table->string('phone_number')->nullable();
            $table->string('social_profiles')->nullable();

            $table->text('comment')->nullable();

            $table->json('extra')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
