<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ContractPoint;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\MarqueeItem;
use App\Models\Post;
use App\Models\ProcessStep;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Support\Seo;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'seo' => Seo::for('home'),
            'services' => Service::live(),
            'sectors' => Sector::live(),
            'features' => Feature::live(),
            'steps' => ProcessStep::live(),
            'stats' => Stat::live(),
            'marquee' => MarqueeItem::live(),
            'testimonials' => Testimonial::live(),
            'contractPoints' => ContractPoint::live(),
            'projects' => Project::query()->active()->ordered()->take(6)->get(),
        ]);
    }

    public function about()
    {
        return view('site.about', [
            'seo' => Seo::for('about', ['crumbs' => ['نبذة عنا' => route('about')]]),
            'aboutParagraphs' => array_filter(preg_split('/\n{2,}/', (string) Setting::get('about_body'))),
            'aboutValues' => array_filter(array_map('trim', explode("\n", (string) Setting::get('about_values')))),
            'yearsStat' => Stat::query()->active()->where('is_counter', true)->ordered()->first(),
            'stats' => Stat::live(),
            'features' => Feature::live(),
            'sectors' => Sector::live(),
            'testimonials' => Testimonial::live(),
        ]);
    }

    public function services()
    {
        return view('site.services', [
            'seo' => Seo::for('services', ['crumbs' => ['خدماتنا' => route('services')]]),
            'services' => Service::live(),
            'sectors' => Sector::live(),
            'steps' => ProcessStep::live(),
            'features' => Feature::live(),
            'contractPoints' => ContractPoint::live(),
        ]);
    }

    public function service(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('site.service', [
            'seo' => Seo::for("service.{$service->slug}", [
                'title' => config("seo.pages.service.{$service->slug}.title") ?? $service->title.' بالرياض | كنوز العالم للمصاعد',
                'description' => config("seo.pages.service.{$service->slug}.description") ?? $service->body,
                'image' => $service->image,
                'crumbs' => ['خدماتنا' => route('services'), $service->title => route('services.show', $service)],
                'jsonld' => [Seo::service($service->title, (string) $service->body)],
            ]),
            'service' => $service,
            'steps' => ProcessStep::live(),
            'others' => Service::query()->active()->ordered()->whereKeyNot($service->id)->get(),
        ]);
    }

    public function contracts()
    {
        $faqs = Faq::live();

        return view('site.contracts', [
            'seo' => Seo::for('contracts', [
                'crumbs' => ['عقود الصيانة' => route('contracts')],
                'jsonld' => [
                    Seo::service('عقد صيانة مصاعد سنوي', 'عقد صيانة مصاعد سنوي بالرياض يشمل زيارات دورية وأولوية طوارئ وخصماً على قطع الغيار.'),
                    Seo::faqPage($faqs),
                ],
            ]),
            'contractPoints' => ContractPoint::live(),
            'stats' => Stat::live(),
            'faqs' => $faqs,
        ]);
    }

    public function gallery()
    {
        return view('site.gallery', [
            'seo' => Seo::for('gallery', ['crumbs' => ['أعمالنا' => route('gallery')]]),
            'projects' => Project::live(),
            'stats' => Stat::live(),
        ]);
    }

    public function project(Project $project)
    {
        abort_unless($project->is_active, 404);

        return view('site.project', [
            'seo' => Seo::for('gallery', [
                'title' => $project->title.' | أعمال كنوز العالم للمصاعد بالرياض',
                'description' => $project->body ?: $project->title.' — من أعمال كنوز العالم للمصاعد في الرياض.',
                'image' => $project->image,
                'crumbs' => ['أعمالنا' => route('gallery'), $project->title => route('gallery.show', $project)],
            ]),
            'project' => $project,
            'others' => Project::query()->active()->ordered()->whereKeyNot($project->id)->take(6)->get(),
        ]);
    }

    public function testimonials()
    {
        return view('site.testimonials', [
            'seo' => Seo::for('testimonials', ['crumbs' => ['آراء العملاء' => route('testimonials')]]),
            'testimonials' => Testimonial::live(),
            'clients' => collect(config('clients.list', [])),
            'stats' => Stat::live(),
        ]);
    }

    public function blog()
    {
        return view('site.blog', [
            'seo' => Seo::for('blog', ['crumbs' => ['المقالات' => route('blog.index')]]),
            'posts' => Post::query()->published()->orderByDesc('published_at')->paginate(9),
        ]);
    }

    public function post(Post $post)
    {
        abort_unless($post->is_active, 404);

        // المقالات المنقولة بلا نص كامل تُحوَّل لمصدرها بدل عرض صفحة فارغة
        if (! $post->body && $post->source_url) {
            return redirect()->away($post->source_url, 302);
        }

        return view('site.post', [
            'seo' => Seo::for('blog', [
                'title' => $post->title.' | كنوز العالم للمصاعد',
                'description' => $post->excerpt,
                'image' => $post->image,
                'crumbs' => ['المقالات' => route('blog.index'), $post->title => route('blog.show', $post)],
                'jsonld' => [Seo::article($post)],
            ]),
            'post' => $post,
            'related' => Post::query()->published()->whereKeyNot($post->id)
                ->where('tag', $post->tag)->ordered()->take(3)->get(),
        ]);
    }

    public function contact()
    {
        $faqs = Faq::live();

        return view('site.contact', [
            'seo' => Seo::for('contact', [
                'crumbs' => ['تواصل معنا' => route('contact')],
                'jsonld' => [Seo::faqPage($faqs)],
            ]),
            'services' => Service::live(),
            'faqs' => $faqs,
        ]);
    }
}
