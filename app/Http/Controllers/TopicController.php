<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TopicController extends Controller
{
    public function topic($id, $type)
    {
        $lang = Session::get('lang', 'lv');
        $topic = DB::table('topics')->where('id', $id)->first();
        if (!$topic) {
            return redirect()->route($lang . '.courses.index')->with('error', 'Tēma netika atrasta...');
        }

        $courses = DB::table('courses')->get();
        $course_id = $topic->course_id;

        $course = DB::table('courses')->where('id', $course_id)->first();


        $title = $topic->{'title_' . Session::get('lang', 'lv')} ?? $topic->title_en;


        return view('courses.topic', compact('topic', 'title', 'course', 'course_id', 'courses', 'type'));
    }

    public function topics()
    {
        $lang = Session::get('lang', 'lv');
        $topics = DB::table('topics')->get();
        $courses = DB::table('courses')->get();

        switch ($lang) {
            case 'ua':
                $title = 'Темы';
                break;
            case 'ru':
                $title = 'Темы';
                break;
            case 'lv':
                $title = 'Tēmas';
                break;
            default:
                $title = 'Topics';
        }

        return view('courses.topics', compact('topics', 'courses', 'title'));
    }
}
