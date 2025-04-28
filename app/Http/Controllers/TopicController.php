<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    public function topic($id)
    {
        $topic = DB::table('topics')->where('id', $id)->first();
        if (!$topic) {
            return redirect()->route('courses.index')->with('error', 'Tēma netika atrasta...');
        }

        $courses = DB::table('courses')->get();
        $course_id = $topic->course_id;

        $course = DB::table('courses')->where('id', $course_id)->first();


        $title = $topic->{'title_' . app()->getLocale()} ?? $topic->title_en;


        return view('courses.topic', compact('topic', 'title', 'course', 'course_id', 'courses'));
    }
}
