<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $courses = Course::all();
        $title = 'Courses';
        return view('courses.index', compact('courses', 'title'));
    }

    public function module($id)
    {
        $module = DB::table('courses')->where('id', $id)->first();
        if (!$module) {
            return redirect()->route('courses.index')->with('error', 'Course not found');
        }

        $tests = DB::table('tests')->where('course_id', $id)->orderBy('order')->get();
        $topics = DB::table('topics')->where('course_id', $id)->orderBy('order')->get();
        $dictionaries = DB::table('dictionaries')->where('course_id', $id)->orderBy('order')->get();

        $lang = app()->getLocale();

        $items = $topics->map(function ($topic) use ($lang) {
            return ['type' => 'topic', 'id' => $topic->id, 'title' => $topic->{'title_' . $lang}, 'order' => $topic->order];
        })->merge($tests->map(function ($test) use ($lang) {
            return ['type' => 'test', 'id' => $test->id, 'title' => $test->{'title_' . $lang}, 'order' => $test->order];
        })->merge($dictionaries->map(function ($dictionary) use ($lang) {
            return ['type' => 'dictionary', 'id' => $dictionary->id, 'title' => $dictionary->{'title_' . $lang}, 'order' => $dictionary->order];
        })))->sortBy('order');

        $courses = Course::all();
        if (app()->getLocale() == 'ua') {
            $title = $module->title_uk ?? $module->title_en;
        } else {
            $title = $course->{'title_' . app()->getLocale()} ?? $module->title_en;
        }
        return view('courses.module', compact('module', 'title', 'courses', 'items'));
    }

    public function dictionary($id)
    {
        $dictionary = DB::table('dictionaries')->where('id', $id)->first();
        if (!$dictionary) {
            return redirect()->route('courses.index')->with('error', 'Vārdnīca netika atrasta...');
        }

        $items = DB::table('translations')->where('dictionary_id', $id)->orderBy('order')->get();

        $course_id = $dictionary->course_id;
        $courses = Course::all();

        $title = $dictionary->{'title_' . app()->getLocale()} ?? $dictionary->title_en;

        return view('courses.dictionary', compact('dictionary', 'title', 'courses', 'items', 'course_id'));
    }
}