<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class CertificateController extends Controller
{



    public function certificates()
    {
        $lang = Session::get('lang', 'lv');

        $courses = Course::all();
        $certificates = DB::table('certificates')
            ->where('certificates.user_id', auth()->user()->id)
            ->select('certificates.*', 'courses.*')
            ->join('users', 'certificates.user_id', '=', 'users.id')
            ->join('courses', 'certificates.course_id', '=', 'courses.id')
            ->orderBy('certificates.is_read', 'asc')
            ->orderBy('certificates.issued_at', 'desc')
            ->get();
        switch ($lang) {
            case 'ua':
                $title = 'Сертифікати';
                break;
            case 'ru':
                $title = 'Сертификаты';
                break;
            case 'lv':
                $title = 'Sertifikāti';
                break;
            default:
                $title = 'Certificates';
        }

        return view('courses.certificates', [
            'certificates' => $certificates,
            'courses' => $courses,
            'title' => $title,
        ]);
    }


    public function download($userID, $courseID)
    {
        $certificate = DB::table('certificates')
            ->where('user_id', $userID)
            ->where('course_id', $courseID)
            ->first();

        if (!$certificate) {
            return response()->json(['error' => 'Certificate not found'], 404);
        }
        DB::table('certificates')
            ->where('user_id', $userID)
            ->where('course_id', $courseID)
            ->update(['is_read' => 1]);

        $filePath = $certificate->certificate_path;

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'Certificate file missing from storage'], 404);
        }

        return Storage::disk('public')->download($filePath);
    }


}
