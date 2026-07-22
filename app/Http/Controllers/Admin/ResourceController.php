<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Resource;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * كنترولر واحد يخدم كل موارد اللوحة اعتماداً على تعريفها في App\Admin\Resource.
 */
class ResourceController extends Controller
{
    public function index(string $resource)
    {
        $def = Resource::find($resource);
        $model = $def['model'];

        return view('admin.resource.index', [
            'def' => $def,
            'rows' => $model::query()->orderBy('sort')->orderBy('id')->get(),
        ]);
    }

    public function create(string $resource)
    {
        $def = Resource::find($resource);

        return view('admin.resource.form', [
            'def' => $def,
            'row' => new $def['model'],
        ]);
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $def = Resource::find($resource);
        $row = new $def['model'];
        $this->fill($request, $row, $def);

        return redirect()->route('admin.resource.index', $resource)
            ->with('ok', 'تمت الإضافة بنجاح.');
    }

    public function edit(string $resource, int $id)
    {
        $def = Resource::find($resource);

        return view('admin.resource.form', [
            'def' => $def,
            'row' => $def['model']::findOrFail($id),
        ]);
    }

    public function update(Request $request, string $resource, int $id): RedirectResponse
    {
        $def = Resource::find($resource);
        $row = $def['model']::findOrFail($id);
        $this->fill($request, $row, $def);

        return redirect()->route('admin.resource.index', $resource)
            ->with('ok', 'تم حفظ التعديلات.');
    }

    public function destroy(string $resource, int $id): RedirectResponse
    {
        $def = Resource::find($resource);
        $row = $def['model']::findOrFail($id);

        // نحذف الصورة المرفوعة معه، ولا نمسّ الروابط الخارجية
        if (! empty($row->image) && ! Str::startsWith($row->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($row->image);
        }
        $row->delete();

        return redirect()->route('admin.resource.index', $resource)
            ->with('ok', 'تم الحذف.');
    }

    /** تبديل حالة الظهور من قائمة الجدول مباشرة. */
    public function toggle(string $resource, int $id): RedirectResponse
    {
        $def = Resource::find($resource);
        $row = $def['model']::findOrFail($id);
        $row->update(['is_active' => ! $row->is_active]);

        return back()->with('ok', $row->is_active ? 'أصبح ظاهراً في الموقع.' : 'أصبح مخفياً عن الموقع.');
    }

    /** حفظ الترتيب الجديد القادم من السحب والإفلات. */
    public function reorder(Request $request, string $resource): RedirectResponse
    {
        $def = Resource::find($resource);
        foreach ((array) $request->input('order', []) as $position => $id) {
            $def['model']::whereKey($id)->update(['sort' => $position + 1]);
        }

        return back()->with('ok', 'تم حفظ الترتيب.');
    }

    /** التحقق من المدخلات ثم الحفظ — منطق مشترك بين الإضافة والتعديل. */
    private function fill(Request $request, Model $row, array $def): void
    {
        $rules = [];
        foreach ($def['fields'] as $name => $field) {
            if ($field['type'] === 'image') {
                $rules[$name.'_file'] = 'nullable|image|max:4096';
                $rules[$name] = 'nullable|string|max:500';
            } elseif (isset($field['rules'])) {
                $rules[$name] = $field['rules'];
            }
        }
        $data = $request->validate($rules);

        foreach ($def['fields'] as $name => $field) {
            switch ($field['type']) {
                case 'image':
                    if ($request->hasFile($name.'_file')) {
                        $old = $row->{$name};
                        $row->{$name} = $request->file($name.'_file')->store('uploads', 'public');
                        if ($old && ! Str::startsWith($old, ['http://', 'https://'])) {
                            Storage::disk('public')->delete($old);
                        }
                    } elseif (array_key_exists($name, $data)) {
                        $row->{$name} = $data[$name];
                    }
                    break;

                case 'list':
                    // سطر لكل عنصر → مصفوفة
                    $row->{$name} = array_values(array_filter(array_map(
                        'trim', preg_split('/\r?\n/', (string) $request->input($name))
                    ), fn ($v) => $v !== ''));
                    break;

                case 'bool':
                    $row->{$name} = $request->boolean($name);
                    break;

                case 'slug':
                    $value = trim((string) $request->input($name));
                    if ($value === '' && isset($field['from'])) {
                        $value = $this->slug((string) $request->input($field['from']));
                    }
                    $row->{$name} = $value;
                    break;

                default:
                    $row->{$name} = $request->input($name);
            }
        }

        if ($row->exists === false && $row->sort === null) {
            $row->sort = ($def['model']::max('sort') ?? 0) + 1;
        }
        $row->is_active = $request->boolean('is_active');
        $row->save();
    }

    /** slug يحافظ على الحروف العربية (Str::slug يحذفها). */
    private function slug(string $text): string
    {
        return trim(preg_replace('/[\s_]+/u', '-',
            preg_replace('/[^\p{Arabic}\p{L}\p{N}\s-]+/u', '', $text)), '-');
    }
}
