<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('due_date')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->softDeletes();
            $table->timestamps();
            
            
            $table->index(['status', 'priority']);
            $table->index(['due_date']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};