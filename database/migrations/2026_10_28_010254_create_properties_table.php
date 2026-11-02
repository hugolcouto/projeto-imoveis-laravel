<?php

use App\Domain\Properties\Enums\TransactionTypeEnum;
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
            Schema::create('properties', function (Blueprint $table) {
                $table->id(); // primary key auto-increment

                $table->string('title', 40); // match max:40 from validation
                $table->string('slug'); // unique, generated from title
                $table->integer('valueInCents'); // min:0 from validation
                $table->string('description', 255);
                $table->json('amenities');
                $table->string('location', 255);
                $table->enum('transactionType', [
                    TransactionTypeEnum::BUY, TransactionTypeEnum::RENTAL
                ]);

                $table->timestamps(); // created_at, updated_at

                $table->unique('slug'); // slug deve ser único
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('properties');
        }
    };
