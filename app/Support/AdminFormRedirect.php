<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminFormRedirect
{
    public const FLASH_MESSAGE = 'Šo lapu nevar atvērt tieši. Lūdzu izmantojiet pogu Saglabāt.';

    public static function fromRequest(Request $request): ?RedirectResponse
    {
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return null;
        }

        $path = trim($request->path(), '/');

        return match (true) {
            preg_match('#^topic/update/(\d+)$#', $path, $m) => self::topic((int) $m[1]),
            preg_match('#^test/update/(\d+)$#', $path, $m) => self::test((int) $m[1]),
            preg_match('#^module/update/(\d+)$#', $path, $m) => self::module((int) $m[1]),
            preg_match('#^dictionary/update/(\d+)$#', $path, $m) => self::dictionary((int) $m[1]),
            default => null,
        };
    }

    public static function topic(int $id): RedirectResponse
    {
        $topic = DB::table('topics')->where('id', $id)->first();
        if (!$topic) {
            return redirect()->route('topics');
        }

        return redirect()->route('topic.edit', [$id, $topic->course_id])->with('info', self::FLASH_MESSAGE);
    }

    public static function test(int $id): RedirectResponse
    {
        $test = DB::table('tests')->where('id', $id)->first();
        if (!$test) {
            return redirect()->route('topics');
        }

        return redirect()->route('test.edit', [$id, $test->course_id])->with('info', self::FLASH_MESSAGE);
    }

    public static function module(int $id): RedirectResponse
    {
        if (!DB::table('courses')->where('id', $id)->exists()) {
            return redirect()->route('module.all');
        }

        return redirect()->route('module.edit', $id)->with('info', self::FLASH_MESSAGE);
    }

    public static function dictionary(int $id): RedirectResponse
    {
        if (!DB::table('dictionaries')->where('id', $id)->exists()) {
            return redirect()->route('topics');
        }

        return redirect()->route('dictionary.edit', $id)->with('info', self::FLASH_MESSAGE);
    }
}
