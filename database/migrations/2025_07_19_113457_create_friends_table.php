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
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sender_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("reciever_id")->constrained("users")->onDelete("cascade");
            $table->unique(["sender_id","reciever_id"]);
            $table->enum("status",["pending","rejected","accepted"])->default("pending");//i give him the list of accepted values for the status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
