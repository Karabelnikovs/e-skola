<!DOCTYPE html>
<html>

<head>
    <title>{{ $congrats }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }

        h1 {
            color: #36225f;
        }

        .content {
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
                alt="Vizii Logo" class="logo" />
            <svg xmlns="http://www.w3.org/2000/svg" width="83" height="36" class="mb-4" viewBox="0 0 83 36"
                fill="none">
                <path d="M0 12.4503H4.94253L11.9024 28.3754L18.8623 12.4503H23.704L13.4911 35.7504H10.2129L0 12.4503Z"
                    fill="#36225f"></path>
                <path d="M27.2598 12.4503H32.0006V35.7504H27.2598V12.4503Z" fill="#36225f"></path>
                <path
                    d="M49.8794 16.6503H37.7248V12.4503H56.4862V15.4253L43.5247 31.5254H57.4697V35.7504H36.9683V32.7754L49.8794 16.6503Z"
                    fill="#36225f"></path>
                <path d="M62.0591 12.4503H66.7999V35.7504H62.0591V12.4503Z" fill="#36225f"></path>
                <path d="M73.1797 12.4503H77.9205V35.7504H73.1797V12.4503Z" fill="#36225f"></path>
                <path
                    d="M75.626 0C74.5164 1.95 72.4234 3.275 70.0026 3.275C67.5817 3.275 65.4635 1.95 64.3792 0L60.4453 2.25C62.3618 5.525 65.9174 7.75 70.0278 7.75C74.1129 7.75 77.6938 5.55 79.6103 2.25L75.626 0Z"
                    fill="#36225f"></path>
            </svg>
        </div>
        <h1>{{ $congrats }}, {{ $user->name }}!</h1>
        <div class="content">
            {!! $body !!}
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
