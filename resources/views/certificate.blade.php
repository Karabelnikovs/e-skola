<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .logo {
            width: 32px;
            height: 32px;
        }

        .title {
            font-size: 24px;
            margin: 20px 0;
        }

        .user-data {
            font-size: 18px;
            margin: 10px 0;
        }

        .course-name {
            font-size: 20px;
            margin: 20px 0;
        }
    </style>
</head>
@php
    $lang = Session::get('lang', 'lv');
@endphp

<body>
    <img src="{{ asset('assets\img\vizii.png') }}" alt="Vizii Logo" class="logo">
    <h1 class="title">Vizii E-skola</h1>
    <p class="user-data">This certificate is awarded to</p>
    <p class="user-data">{{ $user->name }}</p>
    <p class="user-data">for successfully completing the course</p>
    <p class="course-name">{{ $course->{'title_' . $lang} }}</p>
    <p>Issued on: {{ $issued_at->format('Y-m-d') }}</p>
</body>

</html>
