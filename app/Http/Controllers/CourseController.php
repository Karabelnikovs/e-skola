<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\UserProgress;
use App\Models\Contacts;
use App\Models\Terms;
use App\Models\Privacy;

class CourseController extends Controller
{
    public function index()
    {
        $lang = Session::get('lang', 'lv');
        if (!auth()->check()) {
            return redirect()->route($lang . '.login');
        }
        $courses = Course::all();
        $title = 'Courses';
        return view('courses.index', compact('courses', 'title'));
    }

    public function module($id)
    {
        $lang = Session::get('lang', 'lv');
        $module = DB::table('courses')->where('id', $id)->first();
        if (!$module) {
            return redirect()->route($lang . '.courses.index')->with('error', 'Course not found');
        }

        $tests = DB::table('tests')->where('course_id', $id)->orderBy('order')->get();
        $topics = DB::table('topics')->where('course_id', $id)->orderBy('order')->get();
        $dictionaries = DB::table('dictionaries')->where('course_id', $id)->orderBy('order')->get();

        $lang = Session::get('lang', 'lv');

        $items = $topics->map(function ($topic) use ($lang) {
            return ['type' => 'topic', 'id' => $topic->id, 'title' => $topic->{'title_' . $lang}, 'order' => $topic->order];
        })->merge($tests->map(function ($test) use ($lang) {
            return ['type' => 'test', 'id' => $test->id, 'title' => $test->{'title_' . $lang}, 'order' => $test->order];
        })->merge($dictionaries->map(function ($dictionary) use ($lang) {
            return ['type' => 'dictionary', 'id' => $dictionary->id, 'title' => $dictionary->{'title_' . $lang}, 'order' => $dictionary->order];
        })))->sortBy('order');



        $orders = $items->pluck('order')->values()->all();
        $lastOrder = end($orders);

        $current_order = DB::table('user_progress')
            ->where('user_id', auth()->user()->id)
            ->where('course_id', $id)
            ->value('current_order') ?? 0;

        // dd($current_order, $orders[0]);
        if ($current_order == $lastOrder) {
            $order_status = 'last';
        } elseif ($current_order == $orders[0]) {
            $order_status = 'first';
        } elseif (!in_array($current_order, $orders) && $current_order == 0) {
            $order_status = 'first';
        } else {
            $order_status = 'middle';
        }

        $user = auth()->user();
        $currentItem = null;

        $userProgress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $id],
            ['current_order' => 0]
        );

        switch ($lang) {
            case 'ua':
                $message = 'Модуль порожній...';
                break;
            case 'ru':
                $message = 'Модуль пуст...';
                break;
            case 'lv':
                $message = 'Modulis ir tukšs...';
                break;
            default:
                $message = 'Module is empty...';
        }
        if (empty($orders)) {
            return redirect()->route($lang . '.courses.index')->with('error', $message);
        }

        $currentOrder = $userProgress->current_order;

        if ($currentOrder == 0) {
            $currentOrder = $orders[0];
            $userProgress->current_order = $currentOrder;
            $userProgress->save();
        }

        $currentItem = $items->where('order', $currentOrder)->first();

        if (!$currentItem) {
            $userProgress->current_order = $orders[0];
            $userProgress->save();
            $currentItem = $items->first();
        }


        if ($currentItem) {
            $routeName = $lang . '.courses.' . $currentItem['type'];
            return redirect()->route($routeName, ['course' => $id, 'id' => $currentItem['id'], 'order_status' => $order_status]);
        }

        return redirect()->route($lang . '.courses.index')->with('error', 'Module progress error');



        // $courses = Course::all();
        // if (Session::get('lang', 'lv') == 'ua') {
        //     $title = $module->title_uk ?? $module->title_en;
        // } else {
        //     $title = $course->{'title_' . Session::get('lang', 'lv')} ?? $module->title_en;
        // }


        // return view('.courses.module', compact('module', 'title', 'courses', 'items'));
    }

    public function next($id)
    {
        $lang = Session::get('lang', 'lv');
        $user = auth()->user();

        if (!$user) {
            return redirect()->route($lang . '.courses.module', $id)->with('error', 'You must be logged in.');
        }

        $userProgress = UserProgress::where('user_id', $user->id)
            ->where('course_id', $id)
            ->firstOrFail();

        $tests = DB::table('tests')->where('course_id', $id)->orderBy('order')->get();
        $topics = DB::table('topics')->where('course_id', $id)->orderBy('order')->get();
        $dictionaries = DB::table('dictionaries')->where('course_id', $id)->orderBy('order')->get();


        $items = $topics->map(function ($topic) use ($lang) {
            return ['type' => 'topic', 'id' => $topic->id, 'title' => $topic->{'title_' . $lang}, 'order' => $topic->order];
        })->merge($tests->map(function ($test) use ($lang) {
            return ['type' => 'test', 'id' => $test->id, 'title' => $test->{'title_' . $lang}, 'order' => $test->order];
        })->merge($dictionaries->map(function ($dictionary) use ($lang) {
            return ['type' => 'dictionary', 'id' => $dictionary->id, 'title' => $dictionary->{'title_' . $lang}, 'order' => $dictionary->order];
        })))->sortBy('order');

        $orders = $items->pluck('order')->values()->all();
        $lastOrder = end($orders);

        $currentIndex = array_search($userProgress->current_order, $orders);

        if ($currentIndex !== false && isset($orders[$currentIndex + 1])) {
            $nextOrder = $orders[$currentIndex + 1];
            $userProgress->current_order = $nextOrder;
            $userProgress->save();
            if ($nextOrder == $lastOrder) {
                $order_status = 'last';
            } elseif ($nextOrder == $orders[0]) {
                $order_status = 'first';
            } else {
                $order_status = 'middle';
            }
            $currentItem = $items->where('order', $nextOrder)->first();
            $routeName = $lang . '.courses.' . $currentItem['type'];
            return redirect()->route($routeName, ['course' => $id, 'id' => $currentItem['id'], 'order_status' => $order_status]);
        }

        return redirect()->route($lang . '.courses.index')->with('success', 'Module completed!');
    }

    public function previous($id)
    {
        $lang = Session::get('lang', 'lv');
        $user = auth()->user();

        if (!$user) {
            return redirect()->route($lang . '.courses.module', $id)->with('error', 'You must be logged in.');
        }

        $userProgress = UserProgress::where('user_id', $user->id)
            ->where('course_id', $id)
            ->firstOrFail();

        $tests = DB::table('tests')->where('course_id', $id)->orderBy('order')->get();
        $topics = DB::table('topics')->where('course_id', $id)->orderBy('order')->get();
        $dictionaries = DB::table('dictionaries')->where('course_id', $id)->orderBy('order')->get();


        $items = $topics->map(function ($topic) use ($lang) {
            return ['type' => 'topic', 'id' => $topic->id, 'title' => $topic->{'title_' . $lang}, 'order' => $topic->order];
        })->merge($tests->map(function ($test) use ($lang) {
            return ['type' => 'test', 'id' => $test->id, 'title' => $test->{'title_' . $lang}, 'order' => $test->order];
        })->merge($dictionaries->map(function ($dictionary) use ($lang) {
            return ['type' => 'dictionary', 'id' => $dictionary->id, 'title' => $dictionary->{'title_' . $lang}, 'order' => $dictionary->order];
        })))->sortBy('order');

        $orders = $items->pluck('order')->values()->all();
        $lastOrder = end($orders);

        $currentIndex = array_search($userProgress->current_order, $orders);

        if ($currentIndex !== false && isset($orders[$currentIndex - 1])) {
            $nextOrder = $orders[$currentIndex - 1];
            $userProgress->current_order = $nextOrder;
            $userProgress->save();
            if ($nextOrder == $lastOrder) {
                $order_status = 'last';
            } elseif ($nextOrder == $orders[0]) {
                $order_status = 'first';
            } else {
                $order_status = 'middle';
            }

            $currentItem = $items->where('order', $nextOrder)->first();
            $routeName = $lang . '.courses.' . $currentItem['type'];
            return redirect()->route($routeName, ['course' => $id, 'id' => $currentItem['id'], 'order_status' => $order_status]);
        }

        return redirect()->route($lang . '.courses.index')->with('success', 'Module completed!');
    }

    public function dictionary($id, $order_status)
    {
        $lang = Session::get('lang', 'lv');
        $dictionary = DB::table('dictionaries')->where('id', $id)->first();
        if (!$dictionary) {
            return redirect()->route($lang . '.courses.index')->with('error', 'Vārdnīca netika atrasta...');
        }

        $items = DB::table('translations')->where('dictionary_id', $id)->orderBy('order')->get();

        $course_id = $dictionary->course_id;
        $courses = Course::all();

        $title = $dictionary->{'title_' . app()->getLocale()} ?? $dictionary->title_en;

        return view('courses.dictionary', compact('dictionary', 'title', 'courses', 'items', 'course_id', 'order_status'));
    }

    public function dictionaries()
    {
        $lang = Session::get('lang', 'lv');
        $dictionaries = DB::table('dictionaries')->orderBy('order')->get();
        $courses = Course::all();
        switch ($lang) {
            case 'ua':
                $title = 'Словники';
                break;
            case 'ru':
                $title = 'Словари';
                break;
            case 'lv':
                $title = 'Vārdnīcas';
                break;
            default:
                $title = 'Dictionaries';
        }
        return view('courses.dictionaries', compact('dictionaries', 'title', 'courses'));
    }

    public function profile()
    {
        $lang = Session::get('lang', 'lv');
        $user = auth()->user();
        $courses = Course::all();
        switch ($lang) {
            case 'ua':
                $title = 'Профіль';
                break;
            case 'ru':
                $title = 'Профиль';
                break;
            case 'lv':
                $title = 'Profils';
                break;
            default:
                $title = 'Profile';
        }

        return view('courses.profile', compact('user', 'title', 'courses'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $lang = Session::get('lang', 'lv');
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'language' => 'required|string|in:lv,en,ru,ua',
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
            'updated_at' => now(),
        ];
        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }
        DB::table('users')->where('id', $user->id)->update($updateData);

        return redirect()->route($lang . '.profile')->with('success', 'Profile updated successfully');
    }



    public function showContacts()
    {
        $lang = Session::get('lang', 'lv');

        switch ($lang) {
            case 'ua':
                $title = 'Контакти';
                break;
            case 'ru':
                $title = 'Контакты';
                break;
            case 'lv':
                $title = 'Kontakti';
                break;
            default:
                $title = 'Contacts';
        }

        $contacts = Contacts::first();
        $courses = Course::all();

        return view('contacts', [
            'contacts' => $contacts,
            'title' => $title,
            'courses' => $courses,
        ]);
    }

    public function showTerms()
    {
        $lang = Session::get('lang', 'lv');
        switch ($lang) {
            case 'ua':
                $title = 'Умови використання';
                break;
            case 'ru':
                $title = 'Условия использования';
                break;
            case 'lv':
                $title = 'Lietošanas noteikumi';
                break;
            default:
                $title = 'Terms of Use';
        }
        $terms = Terms::first();
        $courses = Course::all();

        return view('terms', [
            'terms' => $terms,
            'title' => $title,
            'courses' => $courses,
        ]);
    }


    public function showPrivacy()
    {
        $lang = Session::get('lang', 'lv');
        switch ($lang) {
            case 'ua':
                $title = 'Політика конфіденційності';
                break;
            case 'ru':
                $title = 'Политика конфиденциальности';
                break;
            case 'lv':
                $title = 'Privātuma politika';
                break;
            default:
                $title = 'Privacy Policy';
        }
        $privacy = Privacy::first();
        $courses = Course::all();

        return view('privacy', [
            'privacy' => $privacy,
            'title' => $title,
            'courses' => $courses,
        ]);
    }
}