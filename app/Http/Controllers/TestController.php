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
        $test = DB::table('tests')->where('id', $id)->first();
        if (!$test) {
            return redirect()->route('courses.index')->with('error', 'Tests netika atrasts...');
        }

        $courses = DB::table('courses')->get();
        $course_id = $test->course_id;
        $questions = DB::table('questions')->where('test_id', $id)->orderBy('order')->get();

        $title = $topic->{'title_' . app()->getLocale()} ?? $test->title_en;

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

        foreach ($answers as $questionId => $answerGiven) {
            $question = $questions->where('id', $questionId)->first();
            $isCorrect = $question->correct_answer == $answerGiven;
            DB::table('answers')->insert([
                'attempt_id' => $attemptId,
                'question_id' => $questionId,
                'answer_given' => $answerGiven,
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
}