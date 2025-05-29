<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Topic;
use App\Models\Test;
use App\Models\Question;
use Illuminate\Support\Facades\Http;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Models\Contacts;
use App\Models\Terms;
use App\Models\Privacy;

class AdminController extends Controller
{


    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $path = $file->store('uploads', 'public_uploads');

            $url = asset('storage/uploads/' . basename($path));

            return response()->json(['location' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }



    public function translateText(Request $request)
    {

        //! ukraiņu tulkojumi google translate neiet ar ua vai ukr, bet tikai ar uk 😑

        $request->validate([
            'text' => 'required|string',
            'to' => 'required|string',
            'from' => 'nullable|string',
        ]);
        $translation = new GoogleTranslate('en');
        $result = $translation->setSource($request->from)->setTarget($request->to)->translate($request->text);


        return response()->json(['translated' => $result ?? '[Translation error]']);
    }
    public function index()
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }

        $userscount = DB::table('users')->count();
        $certificatescount = DB::table('certificates')->count();
        $courses = DB::table('courses')->get();

        $title = 'Pārskats';
        return view('admin.index', compact('title', 'userscount', 'certificatescount', 'courses'));
    }

    public function create()
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();

        $title = 'Izveidot jaunu moduli';
        return view('admin.create', compact('title', 'courses'));
    }

    public function store(Request $request)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name_lv' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_ua' => 'required|string|max:255',
            'description_lv' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'description_ua' => 'nullable|string',
            'formFile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = 'assets/img/course.jpg';
        if ($request->hasFile('formFile')) {
            $image = $request->file('formFile');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->move(public_path('uploads/modules'), $filename);
            $imagePath = 'uploads/modules/' . $filename;
        }

        DB::table('courses')->insert([
            'title_lv' => $request->name_lv,
            'title_en' => $request->name_en,
            'title_ru' => $request->name_ru,
            'title_ua' => $request->name_ua,
            'description_lv' => $request->description_lv,
            'description_en' => $request->description_en,
            'description_ru' => $request->description_ru,
            'description_ua' => $request->description_ua,
            'order' => 0,
            'img' => $imagePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);



        return redirect()->route('module.create')->with('success', 'Modulis veiksmīgi izveidots!');
    }

    public function update(Request $request)
    {

        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $validator = Validator::make($request->all(), [
            'name_lv' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_ua' => 'required|string|max:255',
            'description_lv' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'description_ua' => 'nullable|string',
            'formFile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $module = DB::table('courses')->where('id', $request->module_id)->first();
        if (!$module) {
            return redirect()->route('error')->with('reason', 'Modulis netika atrasts!');
        }

        $imagePath = !empty($module->img) ? $module->img : 'assets/img/course.jpg';
        if ($request->hasFile('formFile')) {
            $image = $request->file('formFile');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->move(public_path('uploads/modules'), $filename);
            $imagePath = 'uploads/modules/' . $filename;
        }
        $data = [
            'title_lv' => $request->name_lv,
            'title_en' => $request->name_en,
            'title_ru' => $request->name_ru,
            'title_ua' => $request->name_ua,
            'description_lv' => $request->description_lv,
            'description_en' => $request->description_en,
            'description_ru' => $request->description_ru,
            'description_ua' => $request->description_ua,
            'img' => $imagePath,
        ];

        DB::table('courses')->where('id', $request->module_id)->update($data);

        return redirect()->route('module.edit', $module->id)->with('success', 'Modulis veiksmīgi atjaunots!');
    }

    public function module($id)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();
        $module = DB::table('courses')->where('id', $id)->first();
        if (!$module) {
            return redirect()->route('error')->with('reason', 'Modulis netika atrasts!');
        }

        $tests = DB::table('tests')->where('course_id', $id)->orderBy('order')->get();
        $topics = DB::table('topics')->where('course_id', $id)->orderBy('order')->get();
        $dictionaries = DB::table('dictionaries')->where('course_id', $id)->orderBy('order')->get();

        $items = $topics->map(function ($topic) {
            return ['type' => 'topic', 'id' => $topic->id, 'title' => $topic->title_lv, 'order' => $topic->order];
        })->merge($tests->map(function ($test) {
            return ['type' => 'test', 'id' => $test->id, 'title' => $test->title_lv, 'order' => $test->order, 'test_type' => $test->type];
        })->merge(
                    $dictionaries->map(function ($dictionary) {
                        return ['type' => 'dictionary', 'id' => $dictionary->id, 'title' => $dictionary->title_lv, 'order' => $dictionary->order];
                    })
                ))->sortBy('order');

        $title = 'Modulis: ' . $module->title_lv;
        return view('admin.module', compact('title', 'module', 'courses', 'items'));
    }

    public function edit($id)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();
        $module = DB::table('courses')->where('id', $id)->first();
        if (!$module) {
            return redirect()->route('error')->with('reason', 'Modulis netika atrasts!');
        }

        $title = 'Rediģēt moduli: ' . $module->title_lv;
        return view('admin.create', compact('title', 'module', 'courses'));
    }

    public function all_modules()
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();
        $title = 'Visi moduļi';
        return view('admin.modules', compact('title', 'courses'));
    }

    public function updateSortOrder(Request $request)
    {
        $order = $request->input('order');
        foreach ($order as $index => $item) {
            if ($item['type'] == 'topic') {
                DB::table('topics')->where('id', $item['id'])->update(['order' => $index + 1]);
            } elseif ($item['type'] == 'test') {
                DB::table('tests')->where('id', $item['id'])->update(['order' => $index + 1]);
            } elseif ($item['type'] == 'dictionary') {
                DB::table('dictionaries')->where('id', $item['id'])->update(['order' => $index + 1]);
            }
        }
        return response()->json(['message' => 'Kārtība veiksmīgi atjaunināta.']);
    }

    public function newTopic($course_id)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();
        $module = DB::table('courses')->where('id', $course_id)->first();
        if (!$module) {
            return redirect()->route('error')->with('reason', 'Modulis netika atrasts!');
        }

        $title = 'Jauns tēma: ' . $module->title_lv;
        return view('admin.topic', compact('title', 'module', 'courses'));
    }

    public function storeTopic(Request $request)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title_lv' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'title_ua' => 'required|string|max:255',
            'content_lv' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
        ]);

        $maxOrderTopic = DB::table('topics')->where('course_id', $request->input('course_id'))->max('order');
        $maxOrderTest = DB::table('tests')->where('course_id', $request->input('course_id'))->max('order');
        $maxOrderDictionary = DB::table('dictionaries')->where('course_id', $request->input('course_id'))->max('order');

        $maxOrder = max($maxOrderTopic, $maxOrderTest, $maxOrderDictionary);

        $data = [
            'course_id' => $request->input('course_id'),
            'title_lv' => $request->input('title_lv'),
            'title_en' => $request->input('title_en'),
            'title_ru' => $request->input('title_ru'),
            'title_ua' => $request->input('title_ua'),
            'content_lv' => $request->input('content_lv'),
            'content_en' => $request->input('content_en'),
            'content_ru' => $request->input('content_ru'),
            'content_ua' => $request->input('content_ua'),
            'order' => $maxOrder + 1,
            'section_id' => null,
        ];
        DB::table('topics')->insert($data);

        return redirect()->route('module', $request->input('course_id'))->with('success', 'Tēma izveidota!');
    }


    public function editTopic($id, $course_id)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();
        // $module = DB::table('courses')->where('id', $course_id)->first();
        $title = 'Rediģēt tēmu';

        $topic = DB::table('topics')->where('id', $id)->first();
        return view('admin.topic', compact('topic', 'title', 'courses', 'course_id'));
    }

    public function updateTopic(Request $request, $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title_lv' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'title_ua' => 'required|string|max:255',
            'content_lv' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
        ]);


        $topic = Topic::findOrFail($id);
        $topic->update($data);

        $course_id = $topic->course_id;
        return redirect()->route('topic.edit', [$id, $course_id])->with('success', 'Tēma atjaunota!');
    }



    public function newTest($course_id)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();
        $module = DB::table('courses')->where('id', $course_id)->first();
        if (!$module) {
            return redirect()->route('error')->with('reason', 'Modulis netika atrasts!');
        }

        $title = 'Jauns tests: ' . $module->title_lv;
        return view('admin.test', compact('title', 'module', 'courses'));
    }

    public function storeTest(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_lv' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'title_ua' => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0',
            'course_id' => 'required|exists:courses,id',
            'is_final_test' => 'nullable',
            'questions' => 'required|array',
            'questions.*.question_lv' => 'required|string',
            'questions.*.question_en' => 'required|string',
            'questions.*.question_ru' => 'required|string',
            'questions.*.question_uk' => 'required|string',
            'questions.*.options' => 'required|array|min:2|max:4',
            'questions.*.options.*.lv' => 'required|string',
            'questions.*.options.*.en' => 'required|string',
            'questions.*.options.*.ru' => 'required|string',
            'questions.*.options.*.uk' => 'required|string',
            'questions.*.correct_answer' => 'required|integer|min:0|max:3',
        ]);

        $maxOrderTopic = DB::table('topics')->where('course_id', $request->input('course_id'))->max('order');
        $maxOrderTest = DB::table('tests')->where('course_id', $request->input('course_id'))->max('order');
        $maxOrderDictionary = DB::table('dictionaries')->where('course_id', $request->input('course_id'))->max('order');

        $maxOrder = max($maxOrderTopic, $maxOrderTest, $maxOrderDictionary);

        $type = $request->has('is_final_test') ? 'final' : 'test';

        $test = Test::create([
            'course_id' => $validated['course_id'],
            'title_en' => $validated['title_en'],
            'title_lv' => $validated['title_lv'],
            'title_ru' => $validated['title_ru'],
            'title_ua' => $validated['title_ua'],
            'passing_score' => $validated['passing_score'],
            'order' => $maxOrder + 1,
            'type' => $type,
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            $optionsLv = array_column($questionData['options'], 'lv');
            $optionsEn = array_column($questionData['options'], 'en');
            $optionsRu = array_column($questionData['options'], 'ru');
            $optionsUa = array_column($questionData['options'], 'uk');
            Question::create([
                'test_id' => $test->id,
                'question_lv' => $questionData['question_lv'],
                'question_en' => $questionData['question_en'],
                'question_ru' => $questionData['question_ru'],
                'question_uk' => $questionData['question_uk'],
                'options_lv' => json_encode($optionsLv),
                'options_en' => json_encode($optionsEn),
                'options_ru' => json_encode($optionsRu),
                'options_ua' => json_encode($optionsUa),
                'correct_answer' => $questionData['correct_answer'],
                'order' => $index + 1,
            ]);
        }

        return redirect()->route('module', $validated['course_id'])->with('success', 'Test created successfully.');
    }


    public function editTest($id, $course_id)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();
        $module = DB::table('courses')->where('id', $course_id)->first();
        if (!$module) {
            return redirect()->route('error')->with('reason', 'Modulis netika atrasts!');
        }

        $test = DB::table('tests')->where('id', $id)->first();
        $questions = DB::table('questions')->where('test_id', $id)->get();

        $title = 'Rediģēt testu: ' . $test->title_lv;
        return view('admin.test', compact('title', 'module', 'courses', 'test', 'questions'));
    }


    public function updateTest(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_lv' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'title_ua' => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0',
            'course_id' => 'required|exists:courses,id',
            'is_final_test' => 'nullable',
            'questions' => 'required|array',
            'questions.*.question_lv' => 'required|string',
            'questions.*.question_en' => 'required|string',
            'questions.*.question_ru' => 'required|string',
            'questions.*.question_uk' => 'required|string',
            'questions.*.options' => 'required|array|min:2|max:4',
            'questions.*.options.*.lv' => 'required|string',
            'questions.*.options.*.en' => 'required|string',
            'questions.*.options.*.ru' => 'required|string',
            'questions.*.options.*.uk' => 'required|string',
            'questions.*.correct_answer' => 'required|integer|min:0|max:3',
        ]);

        $test = Test::findOrFail($id);
        $type = $request->has('is_final_test') ? 'final' : 'test';

        $test->update([
            'title_en' => $validated['title_en'],
            'title_lv' => $validated['title_lv'],
            'title_ru' => $validated['title_ru'],
            'title_ua' => $validated['title_ua'],
            'passing_score' => $validated['passing_score'],
            'type' => $type,
        ]);

        Question::where('test_id', $test->id)->delete();

        foreach ($validated['questions'] as $index => $questionData) {
            $optionsLv = array_column($questionData['options'], 'lv');
            $optionsEn = array_column($questionData['options'], 'en');
            $optionsRu = array_column($questionData['options'], 'ru');
            $optionsUa = array_column($questionData['options'], 'uk');
            Question::create([
                'test_id' => $test->id,
                'question_lv' => $questionData['question_lv'],
                'question_en' => $questionData['question_en'],
                'question_ru' => $questionData['question_ru'],
                'question_uk' => $questionData['question_uk'],
                'options_lv' => json_encode($optionsLv),
                'options_en' => json_encode($optionsEn),
                'options_ru' => json_encode($optionsRu),
                'options_ua' => json_encode($optionsUa),
                'correct_answer' => $questionData['correct_answer'],
                'order' => $index + 1,
            ]);
        }

        return redirect()->back()->with('success', 'Tests atjaunots.');
    }


    public function newDictionary($course_id)
    {
        $courses = DB::table('courses')->get();
        $module = DB::table('courses')->where('id', $course_id)->first();
        if (!$module) {
            return redirect()->route('error')->with('reason', 'Modulis netika atrasts!');
        }

        $title = 'Jauna vārdnīca: ' . $module->title_lv;
        return view('admin.dictionary', compact('title', 'module', 'courses'));
    }
    public function storeDictionary(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title_en' => 'required|string|max:255',
            'title_lv' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'title_ua' => 'required|string|max:255',
        ]);
        $maxOrderTopic = DB::table('topics')->where('course_id', $request->input('course_id'))->max('order');
        $maxOrderTest = DB::table('tests')->where('course_id', $request->input('course_id'))->max('order');
        $maxOrderDictionary = DB::table('dictionaries')->where('course_id', $request->input('course_id'))->max('order');

        $maxOrder = max($maxOrderTopic, $maxOrderTest, $maxOrderDictionary);
        $dictionaryId = DB::table('dictionaries')->insertGetId([
            'course_id' => $request->course_id,
            'title_en' => $request->title_en,
            'title_lv' => $request->title_lv,
            'title_ru' => $request->title_ru,
            'title_ua' => $request->title_ua,
            'order' => $maxOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dictionary.edit', [$dictionaryId])->with('success', 'Vārdnīca veiksmīgi izveidota!');

    }
    public function updateDictionary(Request $request, $id)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_lv' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'title_ua' => 'required|string|max:255',
        ]);

        DB::table('dictionaries')->where('id', $id)->update([
            'title_en' => $request->title_en,
            'title_lv' => $request->title_lv,
            'title_ru' => $request->title_ru,
            'title_ua' => $request->title_ua,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Vārdnīca atjaunota!');
    }

    public function editDictionary($id)
    {
        $dictionary = DB::table('dictionaries')->where('id', $id)->first();
        if (!$dictionary) {
            return redirect()->route('error')->with('reason', 'Vārdnīca netika atrasta!');
        }

        $module = DB::table('courses')->where('id', $dictionary->course_id)->first();
        $courses = DB::table('courses')->get();
        $translations = DB::table('translations')
            ->where('dictionary_id', $id)
            ->orderBy('order')
            ->get();

        $title = 'Rediģēt vārdnīcu: ' . $module->title_lv;
        return view('admin.dictionary', compact('title', 'module', 'courses', 'dictionary', 'translations'));
    }
    public function storeTranslation(Request $request, $dictionaryId)
    {
        $request->validate([
            'phrase_en' => 'required|string|max:255',
            'phrase_lv' => 'required|string|max:255',
            'phrase_ru' => 'required|string|max:255',
            'phrase_ua' => 'required|string|max:255',
        ]);

        $maxOrder = DB::table('translations')
            ->where('dictionary_id', $dictionaryId)
            ->max('order') ?? 0;

        $translationId = DB::table('translations')->insertGetId([
            'dictionary_id' => $dictionaryId,
            'phrase_en' => $request->phrase_en,
            'phrase_lv' => $request->phrase_lv,
            'phrase_ru' => $request->phrase_ru,
            'phrase_ua' => $request->phrase_ua,
            'order' => $maxOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'id' => $translationId]);
    }

    public function updateTranslation(Request $request, $id)
    {
        $request->validate([
            'phrase_en' => 'required|string|max:255',
            'phrase_lv' => 'required|string|max:255',
            'phrase_ru' => 'required|string|max:255',
            'phrase_ua' => 'required|string|max:255',
        ]);

        DB::table('translations')
            ->where('id', $id)
            ->update([
                'phrase_en' => $request->phrase_en,
                'phrase_lv' => $request->phrase_lv,
                'phrase_ru' => $request->phrase_ru,
                'phrase_ua' => $request->phrase_ua,
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    public function deleteTranslation($id)
    {
        DB::table('translations')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function updateTranslationOrder(Request $request, $dictionaryId)
    {
        $order = $request->input('order');
        foreach ($order as $index => $translationId) {
            DB::table('translations')
                ->where('id', $translationId)
                ->where('dictionary_id', $dictionaryId)
                ->update(['order' => $index + 1]);
        }
        return response()->json(['message' => 'Kārtība veiksmīgi atjaunināta.']);
    }

    public function users()
    {
        $courses = DB::table('courses')->get();
        $users = DB::table('users')->orderBy('created_at', 'desc')->get();
        $title = 'Lietotāji';
        return view('admin.users', compact('title', 'users', 'courses'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
                'language' => 'required|string|in:lv,en,ru,ua',
                'role' => 'required|string',
            ],
            [
                'email.unique' => 'Šis e-pasts jau ir reģistrēts!',
                'password.confirmed' => 'Parole un apstiprinājums nesakrīt!',
                'name.required' => 'Lūdzu ievadiet vārdu!',
                'email.required' => 'Lūdzu ievadiet e-pastu!',
                'password.required' => 'Lūdzu ievadiet paroli!',
                'password_confirmation.required' => 'Lūdzu apstipriniet paroli!',
            ]
        );

        $hashedPassword = bcrypt($validated['password']);
        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $hashedPassword,
            'language' => $validated['language'],
            'role' => $validated['role'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'Lietotājs veiksmīgi izveidots!');
    }


    public function updateUser(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'Lietotājs nav atrasts!');
        }

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
                'language' => 'required|string|in:lv,en,ru,ua',
                'role' => 'required|string',
            ],
            [
                'email.unique' => 'Šis e-pasts jau ir reģistrēts!',
                'password.confirmed' => 'Parole un apstiprinājums nesakrīt!',
                'name.required' => 'Lūdzu ievadiet vārdu!',
                'email.required' => 'Lūdzu ievadiet e-pastu!',
            ]
        );

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'language' => $validated['language'],
            'role' => $validated['role'],
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return redirect()->route('admin.users')->with('success', 'Lietotājs veiksmīgi atjaunināts!');
    }
    public function destroyUser($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('admin.users')->with('success', 'Lietotājs veiksmīgi izdzēsts!');
    }


    public function usersTests()
    {
        $courses = DB::table('courses')->get();
        $users = DB::table('users')->orderBy('created_at', 'desc')->get();
        $title = 'Lietotāju testi';
        return view('admin.tests.users', compact('title', 'users', 'courses'));
    }

    public function testsHistory($id)
    {
        $courses = DB::table('courses')->get();
        $title = 'Lietotāja testi';
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'Lietotājs nav atrasts!');
        }

        $tests = DB::table('tests')
            ->join('courses', 'tests.course_id', '=', 'courses.id')
            ->select('tests.*', 'courses.title_en as course_title_en', 'courses.title_lv as course_title_lv', 'courses.title_ua as course_title_ua', 'courses.title_ru as course_title_ru')
            ->orderBy('tests.order', 'asc')
            ->get();

        $tests->map(function ($test) use ($id) {
            $hasPassed = DB::table('attempts')
                ->where('test_id', $test->id)
                ->where('user_id', $id)
                ->where('passed', 1)
                ->exists();
            $test->passed = $hasPassed ? 1 : 0;
        });
        $tests->map(function ($test) use ($id) {
            $test->attempts_count = DB::table('attempts')
                ->where('test_id', $test->id)
                ->where('user_id', $id)
                ->count() ?? 0;
        });

        return view('admin.tests.tests', compact('tests', 'courses', 'title', 'user'));

    }

    public function attempts($id, $userID)
    {
        $courses = DB::table('courses')->get();
        $title = 'Mēģinājumu vēsture';
        $attempts = DB::table('attempts')
            ->where('attempts.test_id', $id)
            ->join('tests', 'attempts.test_id', '=', 'tests.id')
            ->where('attempts.user_id', $userID)
            ->select('attempts.*', 'tests.title_en as title_en', 'tests.title_lv as title_lv', 'tests.title_ua as title_ua', 'tests.title_ru as title_ru', 'tests.passing_score')
            ->orderBy('attempts.created_at', 'desc')
            ->get();

        $question_count = DB::table('questions')
            ->where('test_id', $id)
            ->count();

        return view('admin.tests.attempts', compact('attempts', 'courses', 'title', 'question_count', 'id', 'userID'));
    }

    public function attempt($id)
    {
        $title = 'Mēģinājums';
        $courses = DB::table('courses')->get();

        $attempt = DB::table('attempts')
            ->where('attempts.id', $id)
            ->join('tests', 'attempts.test_id', '=', 'tests.id')
            ->select('attempts.*', 'tests.title_en as title_en', 'tests.title_lv as title_lv', 'tests.title_ua as title_ua', 'tests.title_ru as title_ru')
            ->first();

        if (!$attempt) {
            return redirect()->back()->with('error', 'Sūtījums netika atrasts...');
        }
        $test_id = $attempt->test_id;
        $question_count = DB::table('questions')
            ->where('test_id', $test_id)
            ->count();

        $answers = DB::table('answers')
            ->where('attempt_id', $id)
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->select('answers.*', 'questions.question_en as question_en', 'questions.question_lv as question_lv', 'questions.question_uk as question_ua', 'questions.question_ru as question_ru', 'questions.options_en as options_en', 'questions.options_lv as options_lv', 'questions.options_ua as options_ua', 'questions.options_ru as options_ru', 'questions.correct_answer', 'questions.order')
            ->orderBy('questions.order', 'asc')
            ->get();
        $answers->map(function ($answer) {

            foreach (['options_en', 'options_lv', 'options_ua', 'options_ru'] as $field) {
                if (isset($answer->$field)) {
                    $decoded = json_decode($answer->$field, true);
                    if (is_null($decoded)) {
                        $decoded = explode(',', $answer->$field);
                    }
                    $answer->$field = $decoded;
                }
            }

            $optionsField = 'options_lv';
            $options = $answer->$optionsField ?? [];
            $answer->correct_option = $options[$answer->correct_answer] ?? null;

            return $answer;
        });


        return view('admin.tests.attempt', compact('attempt', 'courses', 'title', 'answers', 'test_id', 'question_count'));
    }


    public function topics()
    {
        $topics = DB::table('topics')->get();
        $courses = DB::table('courses')->get();


        $title = 'Tēmas';


        return view('admin.topics', compact('topics', 'courses', 'title'));
    }

    public function topic($id)
    {
        $topic = DB::table('topics')->where('id', $id)->first();


        $courses = DB::table('courses')->get();
        $course_id = $topic->course_id;

        $course = DB::table('courses')->where('id', $course_id)->first();


        $title = $topic->title_lv;


        return view('admin.topic-view', compact('topic', 'title', 'course', 'course_id', 'courses'));
    }


    public function certificatesUsers()
    {
        $courses = DB::table('courses')->get();
        $users = DB::table('users')->orderBy('created_at', 'desc')->get();
        $title = 'Lietotāju sertifikāti';
        return view('admin.certificates.users', compact('title', 'users', 'courses'));
    }

    public function certificates($userID)
    {
        $courses = DB::table('courses')->get();
        $title = 'Lietotāja sertifikāti';
        $user = DB::table('users')->where('id', $userID)->first();
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'Lietotājs nav atrasts!');
        }

        $certificates = DB::table('certificates')
            ->join('courses', 'certificates.course_id', '=', 'courses.id')
            ->select('certificates.*', 'courses.*')
            ->where('certificates.user_id', $userID)
            ->orderBy('certificates.created_at', 'desc')
            ->get();


        return view('admin.certificates.certificates', compact('certificates', 'courses', 'title', 'user'));
    }

    public function contacts()
    {
        $courses = DB::table('courses')->get();
        $title = 'Kontakti';
        $contacts = Contacts::first();

        return view('admin.contact_form', compact('title', 'courses', 'contacts'));
    }
    public function storeContacts(Request $request)
    {
        $data = $request->validate([
            'addresses' => 'array|max:4',
            'addresses.*' => 'string',
            'emails' => 'array|max:4',
            'emails.*' => 'email',
            'phones' => 'array|max:4',
            'phones.*' => 'string|max:50',
        ]);

        Contacts::updateOrCreate(['id' => 1], [
            'phone_numbers' => json_encode($request->phones),
            'emails' => json_encode($request->emails),
            'addresses' => json_encode($request->addresses),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }




    public function useTerms()
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();

        $title = 'Lietošanas noteikumi';
        $terms = Terms::first();

        return view('admin.terms', compact('title', 'courses', 'terms'));
    }

    public function storeTerms(Request $request)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $request->validate([
            'content_lv' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
        ]);


        Terms::updateOrCreate(['id' => 1], [
            'content_lv' => $request->input('content_lv'),
            'content_en' => $request->input('content_en'),
            'content_ru' => $request->input('content_ru'),
            'content_ua' => $request->input('content_ua'),
            'updated_at' => now(),
        ]);

        return redirect()->route('rules')->with('success', 'Noteikumi saglabāti!');
    }

    public function privacy()
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $courses = DB::table('courses')->get();

        $title = 'Privātuma politika';
        $privacy = Privacy::first();

        return view('admin.privacy', compact('title', 'courses', 'privacy'));
    }

    public function storePrivacy(Request $request)
    {
        if (!session('is_admin')) {
            return redirect()->route('courses.index');
        }
        $request->validate([
            'content_lv' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
        ]);


        Privacy::updateOrCreate(['id' => 1], [
            'content_lv' => $request->input('content_lv'),
            'content_en' => $request->input('content_en'),
            'content_ru' => $request->input('content_ru'),
            'content_ua' => $request->input('content_ua'),
            'updated_at' => now(),
        ]);

        return redirect()->route('privacy')->with('success', 'Privātuma politika saglabāta!');
    }

    public function usersProgress()
    {
        $courses = DB::table('courses')->get();
        $users = DB::table('users')->orderBy('created_at', 'desc')->get();
        $title = 'Lietotāju sertifikāti';
        return view('admin.progress.users', compact('title', 'users', 'courses'));
    }
    public function progress($userID)
    {
        $courses = DB::table('courses')->get();

        $user = DB::table('users')->where('id', $userID)->first();
        if (!$user) {
            return redirect()->route('users-progress')->with('error', 'Lietotājs nav atrasts!');
        }

        $progress = DB::table('user_progress')
            ->join('courses', 'user_progress.course_id', '=', 'courses.id')
            ->select('user_progress.*', 'courses.title_en as course_title_en', 'courses.title_lv as course_title_lv', 'courses.title_ua as course_title_ua', 'courses.title_ru as course_title_ru')
            ->where('user_progress.user_id', $userID)
            ->orderBy('user_progress.created_at', 'desc')
            ->get();

        $courseIds = $progress->pluck('course_id')->unique();

        $testsCounts = DB::table('tests')
            ->whereIn('course_id', $courseIds)
            ->select('course_id', DB::raw('COUNT(*) as count'))
            ->groupBy('course_id')
            ->get()
            ->keyBy('course_id');

        $topicsCounts = DB::table('topics')
            ->whereIn('course_id', $courseIds)
            ->select('course_id', DB::raw('COUNT(*) as count'))
            ->groupBy('course_id')
            ->get()
            ->keyBy('course_id');

        $dictionariesCounts = DB::table('dictionaries')
            ->whereIn('course_id', $courseIds)
            ->select('course_id', DB::raw('COUNT(*) as count'))
            ->groupBy('course_id')
            ->get()
            ->keyBy('course_id');

        $progress->transform(function ($prog) use ($testsCounts, $topicsCounts, $dictionariesCounts) {
            $courseId = $prog->course_id;

            $tests = $testsCounts[$courseId]->count ?? 0;
            $topics = $topicsCounts[$courseId]->count ?? 0;
            $dictionaries = $dictionariesCounts[$courseId]->count ?? 0;

            $totalItems = $tests + $topics + $dictionaries;
            $percentage = $totalItems > 0
                ? min(($prog->current_order / $totalItems) * 100, 100)
                : 0;

            $prog->total_items = $totalItems;
            $prog->percentage = round($percentage, 2);
            $prog->completed_items = $prog->current_order;

            return $prog;
        });

        $title = 'Lietotāja progress';
        return view('admin.progress.progress', compact('progress', 'title', 'user', 'courses'));
    }
}
