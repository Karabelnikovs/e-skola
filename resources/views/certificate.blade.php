<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .certificate-container {
            background: white;
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #e0e0e0;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .title {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 18px;
            margin-bottom: 40px;
        }

        .user-name {
            font-size: 32px;
            color: #2980b9;
            margin: 25px 0;
            font-weight: 600;
            text-transform: uppercase;
        }

        .course-name {
            font-size: 24px;
            color: #27ae60;
            margin: 30px 0;
            font-style: italic;
        }

        .certificate-body {
            border-top: 2px solid #2980b9;
            border-bottom: 2px solid #2980b9;
            padding: 40px 0;
            margin: 30px 0;
        }

        .issuance-date {
            font-size: 16px;
            color: #7f8c8d;
            margin-top: 20px;
        }

        .decorative-border {
            position: absolute;
            width: 90%;
            height: 72%;
            border: 2px solid #2980b9;
            top: 5%;
            left: 5%;
            pointer-events: none;
            border-radius: 10px;
        }

        .watermark {
            position: absolute;
            opacity: 0.1;
            font-size: 120px;
            transform: rotate(-45deg);
            top: 30%;
            left: 10%;
            white-space: nowrap;
            color: #2980b9;
        }
    </style>
</head>

@php
    $lang = Session::get('lang', 'lv');
    // Use absolute path for PDF generators
    $logoPath = public_path('assets/img/vizii.png');
@endphp

<body>
    <div class="certificate-container">
        <div class="decorative-border"></div>
        {{-- <div class="watermark">Certificate of Achievement</div> --}}

        <!-- Use file path instead of URL for PDF generators -->
        <img src="{{ $logoPath }}" alt="Vizii Logo" class="logo">

        <h1 class="title">Certificate of Completion</h1>
        <p class="subtitle">This certificate is proudly presented to</p>

        <div class="certificate-body">
            <div class="user-name">{{ $user->name }}</div>
            <p class="subtitle">has successfully completed</p>
            <div class="course-name">«{{ $course->title_en }}»</div>
        </div>

        <div class="issuance-date">
            Issued on: {{ $issued_at->format('F j, Y') }}<br>
            {{-- <em>Valid with verification code: {{ substr(md5($user->id . $course->id), 0, 12) }}</em> --}}
        </div>

        {{-- <div style="margin-top: 40px; display: flex; justify-content: space-between; align-items: center">
            <div style="border-top: 1px solid #7f8c8d; width: 200px; padding-top: 10px">
                Authorized Signature
            </div>
            <div style="border-top: 1px solid #7f8c8d; width: 200px; padding-top: 10px">
                Director of Education
            </div>
        </div> --}}
    </div>
</body>

</html>
