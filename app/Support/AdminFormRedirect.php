<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminFormRedirect
{
    public static function fromRequest(Request $request): ?RedirectResponse
    {
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return null;
        }

        $path = trim($request->path(), '/');

        return match (true) {
            preg_match('#^topic/(?:update|save)/(\d+)$#', $path, $m) => self::topic((int) $m[1], $request),
            preg_match('#^test/update/(\d+)$#', $path, $m) => self::test((int) $m[1]),
            preg_match('#^module/update/(\d+)$#', $path, $m) => self::module((int) $m[1]),
            preg_match('#^dictionary/update/(\d+)$#', $path, $m) => self::dictionary((int) $m[1]),
            $path === 'topics/store' => self::topicStore($request),
            $path === 'tests/store' => redirect()->route('topics'),
            $path === 'dictionary/store' => redirect()->route('topics'),
            $path === 'welcome_email/store' => redirect()->route('welcome_email'),
            $path === 'cookies/store' => redirect()->route('cookies'),
            default => null,
        };
    }

    public static function topic(int $id, ?Request $request = null): RedirectResponse
    {
        $topic = DB::table('topics')->where('id', $id)->first();
        if (!$topic) {
            return redirect()->route('topics');
        }

        $redirect = redirect()->route('topic.edit', [$id, $topic->course_id]);

        if ($request?->isMethod('GET')) {
            Log::warning('topic.update reached as GET (save was not processed)', [
                'id' => $id,
                'referer' => $request->header('referer'),
                'user_agent' => $request->userAgent(),
            ]);

            return $redirect->with(
                'save_error',
                'Saglabāšana netika apstrādāta. Pārlūkprogramma vai serveris nosūtīja GET, nevis POST. Mēģiniet vēlreiz; ja kļūda atkārtojas, pārbaudiet APP_URL (https) un servera pāradresācijas.'
            );
        }

        return $redirect;
    }

    public static function test(int $id): RedirectResponse
    {
        $test = DB::table('tests')->where('id', $id)->first();
        if (!$test) {
            return redirect()->route('topics');
        }

        return redirect()->route('test.edit', [$id, $test->course_id]);
    }

    public static function module(int $id): RedirectResponse
    {
        if (!DB::table('courses')->where('id', $id)->exists()) {
            return redirect()->route('module.all');
        }

        return redirect()->route('module.edit', $id);
    }

    public static function dictionary(int $id): RedirectResponse
    {
        if (!DB::table('dictionaries')->where('id', $id)->exists()) {
            return redirect()->route('topics');
        }

        return redirect()->route('dictionary.edit', $id);
    }

    public static function topicStore(Request $request): RedirectResponse
    {
        $courseId = $request->query('course_id');
        if ($courseId && DB::table('courses')->where('id', $courseId)->exists()) {
            return redirect()->route('topic.new', $courseId);
        }

        return redirect()->route('topics');
    }
}
