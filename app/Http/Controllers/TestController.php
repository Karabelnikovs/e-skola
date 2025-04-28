<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class TestController extends Controller
{
    public function testSession(Request $request)
    {
        Log::info('Testing logging functionality');
        $request->session()->put('test_key', 'Hello from session!');
        return $request->session()->get('test_key');
    }

    public function show($id)
    {
        $lang = Session::get('lang', 'lv');
        $test = DB::table('tests')->where('id', $id)->first();
        if (!$test) {
            return redirect()->route($lang . '.courses.index')->with('error', 'Tests netika atrasts...');
        }

        $courses = DB::table('courses')->get();
        $course_id = $test->course_id;
        $questions = DB::table('questions')->where('test_id', $id)->orderBy('order')->get();

        $title = $topic->{'title_' . Session::get('lang', 'lv')} ?? $test->title_en;

        return view('courses.test', compact('id', 'test', 'courses', 'course_id', 'title', 'questions'));
    }


    public function submit(Request $request, $id)
    {
        $user = Auth::user();

        if (!$request->has('answers') || !is_array($request->input('answers'))) {
            return response()->json(['error' => 'Invalid answers format'], 400);
        }

        $answers = $request->input('answers');

        $test = DB::table('tests')->find($id);
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }

        $questions = DB::table('questions')->where('test_id', $id)->get();
        if ($questions->isEmpty()) {
            return response()->json(['error' => 'No questions found for this test'], 400);
        }

        $questionCount = $questions->count();

        if (count($answers) !== $questionCount) {
            return response()->json(['error' => 'Please answer all questions'], 400);
        }

        $score = 0;
        foreach ($answers as $questionId => $submittedAnswer) {
            $question = $questions->firstWhere('id', $questionId);
            if (!$question) {
                return response()->json(['error' => 'Invalid question ID'], 400);
            }

            $lang = Session::get('lang', 'lv');
            $optionsField = 'options_' . ($lang === 'ua' ? 'uk' : $lang); // Fix for Ukrainian
            $options = json_decode($question->$optionsField) ?? explode(',', $question->$optionsField);

            $correctIndex = (int) $question->correct_answer;
            $correctAnswer = $options[$correctIndex] ?? null;

            if ($correctAnswer === $submittedAnswer) {
                $score++;
            }
        }


        $percentageScore = ($score / $questionCount) * 100;
        $passed = $percentageScore >= $test->passing_score;

        $attemptNumber = DB::table('attempts')
            ->where('user_id', $user->id)
            ->where('test_id', $id)
            ->count() + 1;

        $attemptId = DB::table('attempts')->insertGetId([
            'user_id' => $user->id,
            'test_id' => $id,
            'attempt_number' => $attemptNumber,
            'score' => $score,
            'passed' => $passed,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // foreach ($answers as $questionId => $answerGiven) {
        //     $question = $questions->where('id', $questionId)->first();
        //     $isCorrect = $question->correct_answer == $answerGiven;
        //     DB::table('answers')->insert([
        //         'attempt_id' => $attemptId,
        //         'question_id' => $questionId,
        //         'answer_given' => $answerGiven,
        //         'is_correct' => $isCorrect,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        foreach ($answers as $questionId => $submittedAnswer) {
            $question = $questions->where('id', $questionId)->first();

            $lang = Session::get('lang', 'lv');
            $optionsField = 'options_' . ($lang === 'ua' ? 'uk' : $lang); // same fix
            $options = json_decode($question->$optionsField) ?? explode(',', $question->$optionsField);

            $submittedIndex = array_search($submittedAnswer, $options);

            if ($submittedIndex === false) {
                return response()->json(['error' => 'Invalid submitted answer'], 400);
            }

            $isCorrect = ((int) $question->correct_answer === (int) $submittedIndex);

            DB::table('answers')->insert([
                'attempt_id' => $attemptId,
                'question_id' => $questionId,
                'answer_given' => $submittedIndex,
                'is_correct' => $isCorrect,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'score' => $score,
            'passed' => $passed,
            'percentage' => round($percentageScore, 2)
        ]);
    }

    public function tests()
    {
        $lang = Session::get('lang', 'lv');
        switch ($lang) {
            case 'ua':
                $title = 'Тести';
                break;
            case 'ru':
                $title = 'Тесты';
                break;
            case 'lv':
                $title = 'Testi';
                break;
            default:
                $title = 'Tests';
        }
        $courses = DB::table('courses')->get();
        $user = Auth::user();


        $tests = DB::table('tests')
            ->join('courses', 'tests.course_id', '=', 'courses.id')
            ->select('tests.*', 'courses.title_en as course_title_en', 'courses.title_lv as course_title_lv', 'courses.title_ua as course_title_ua', 'courses.title_ru as course_title_ru')
            ->orderBy('tests.order', 'asc')
            ->get();

        $tests->map(function ($test) {
            $hasPassed = DB::table('attempts')
                ->where('test_id', $test->id)
                ->where('user_id', Auth::user()->id)
                ->where('passed', 1)
                ->exists();
            $test->passed = $hasPassed ? 1 : 0;
        });

        return view('courses.tests', compact('tests', 'courses', 'title'));
    }

    public function attempts($id)
    {
        $lang = Session::get('lang', 'lv');
        switch ($lang) {
            case 'ua':
                $title = 'Спроби';
                break;
            case 'ru':
                $title = 'Попытки';
                break;
            case 'lv':
                $title = 'Mēģinājumi';
                break;
            default:
                $title = 'Attempts';
        }
        $courses = DB::table('courses')->get();
        $user = Auth::user();
        $attempts = DB::table('attempts')
            ->where('attempts.test_id', $id)
            ->join('tests', 'attempts.test_id', '=', 'tests.id')
            ->where('attempts.user_id', $user->id)
            ->select('attempts.*', 'tests.title_en as title_en', 'tests.title_lv as title_lv', 'tests.title_ua as title_ua', 'tests.title_ru as title_ru', 'tests.passing_score')
            ->orderBy('attempts.created_at', 'desc')
            ->get();

        $question_count = DB::table('questions')
            ->where('test_id', $id)
            ->count();

        return view('courses.attempts', compact('attempts', 'courses', 'title', 'question_count'));
    }

    public function attempt($id)
    {
        $lang = Session::get('lang', 'lv');
        switch ($lang) {
            case 'ua':
                $title = 'Спроба';
                break;
            case 'ru':
                $title = 'Попытка';
                break;
            case 'lv':
                $title = 'Mēģinājums';
                break;
            default:
                $title = 'Attempt';
        }
        $courses = DB::table('courses')->get();
        $user = Auth::user();
        $attempt = DB::table('attempts')
            ->where('attempts.id', $id)
            ->join('tests', 'attempts.test_id', '=', 'tests.id')
            ->where('attempts.user_id', $user->id)
            ->select('attempts.*', 'tests.title_en as title_en', 'tests.title_lv as title_lv', 'tests.title_ua as title_ua', 'tests.title_ru as title_ru')
            ->first();

        if (!$attempt) {
            return redirect()->route($lang . '.courses.index')->with('error', 'Sūtījums netika atrasts...');
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
            $lang = Session::get('lang', 'lv');

            foreach (['options_en', 'options_lv', 'options_ua', 'options_ru'] as $field) {
                if (isset($answer->$field)) {
                    $decoded = json_decode($answer->$field, true);
                    if (is_null($decoded)) {
                        $decoded = explode(',', $answer->$field);
                    }
                    $answer->$field = $decoded;
                }
            }

            $optionsField = 'options_' . $lang;
            $options = $answer->$optionsField ?? [];
            $answer->correct_option = $options[$answer->correct_answer] ?? null;

            return $answer;
        });


        return view('courses.attempt', compact('attempt', 'courses', 'title', 'answers', 'test_id', 'question_count'));
    }



}