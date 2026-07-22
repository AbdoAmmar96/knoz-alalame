<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * كل جداول موقع كنوز في هجرة واحدة — المشروع جديد ولا توجد قاعدة سابقة،
 * وتجميعها هنا يجعل مخطط البيانات كله مقروءاً في ملف واحد.
 */
return new class extends Migration
{
    public function up(): void
    {
        // حقول مشتركة لكل جداول المحتوى المرتّبة
        $common = function (Blueprint $t) {
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        };

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->string('group')->default('general');
            $t->timestamps();
        });

        Schema::create('services', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('body')->nullable();
            $t->text('long_body')->nullable();
            $t->string('image')->nullable();
            $t->json('features')->nullable();
            $common($t);
        });

        Schema::create('sectors', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('number', 8)->nullable();
            $t->string('title');
            $t->text('body')->nullable();
            $t->string('image')->nullable();
            $t->text('icon')->nullable();   // SVG خام
            $common($t);
        });

        Schema::create('features', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('title');
            $t->text('body')->nullable();
            $t->text('icon')->nullable();
            $common($t);
        });

        Schema::create('process_steps', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('title');
            $t->text('body')->nullable();
            $t->text('icon')->nullable();
            $common($t);
        });

        Schema::create('projects', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('tag')->nullable();
            $t->string('image')->nullable();
            $common($t);
        });

        Schema::create('posts', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('body')->nullable();
            $t->string('tag')->nullable();
            $t->string('image')->nullable();
            $t->string('source_url')->nullable();  // روابط المقالات القديمة حتى تُنقل نصوصها
            $t->timestamp('published_at')->nullable();
            $common($t);
        });

        Schema::create('faqs', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('question');
            $t->text('answer')->nullable();
            $common($t);
        });

        Schema::create('testimonials', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('name');
            $t->string('role')->nullable();
            $t->text('body')->nullable();
            $t->unsignedTinyInteger('rating')->default(5);
            $common($t);
        });

        Schema::create('stats', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('value');
            $t->string('suffix', 8)->nullable();
            $t->boolean('is_counter')->default(false);
            $t->string('label');
            $common($t);
        });

        Schema::create('marquee_items', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('title');
            $common($t);
        });

        Schema::create('contract_points', function (Blueprint $t) use ($common) {
            $t->id();
            $t->string('title');
            $common($t);
        });

        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone');
            $t->string('service')->nullable();
            $t->text('details')->nullable();
            $t->string('status')->default('new');   // new | contacted | won | lost
            $t->string('ip', 45)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'leads', 'contract_points', 'marquee_items', 'stats', 'testimonials',
            'faqs', 'posts', 'projects', 'process_steps', 'features', 'sectors',
            'services', 'settings',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
