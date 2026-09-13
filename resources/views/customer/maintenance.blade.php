<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body {
            margin: 0;
            background-color: #0f0f0f !important;
        }
        .maintenance-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            background: #0f0f0f;
            padding: 90px 20px 20px 20px;
            box-sizing: border-box;
            margin: 0;
        }
        body.dark-mode .maintenance-container {
            background: #0f0f0f;
        }
        .maintenance-icon {
            font-size: 80px;
            color: #667eea;
            margin-bottom: 20px;
            animation: spin 4s linear infinite;
        }
        .maintenance-title {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 10px;
        }
        body.dark-mode .maintenance-title {
            color: #f1f1f1;
        }
        .maintenance-text {
            font-size: 18px;
            color: #cccccc;
            margin-bottom: 30px;
            max-width: 600px;
            line-height: 1.6;
        }
        body.dark-mode .maintenance-text {
            color: #cccccc;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-back:hover {
            background: linear-gradient(135deg, #5a6fd6, #6a4299);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>
<body>
    @include('layouts.app')
    <div class="maintenance-container">
        <i class="fa-solid fa-cogs maintenance-icon"></i>
        <h1 class="maintenance-title">Sedang Dalam Perbaikan</h1>
        <p class="maintenance-text">
            Mohon maaf, halaman ini saat ini sedang dalam proses perbaikan dan pemeliharaan oleh Admin. 
            Hal ini dilakukan untuk meningkatkan kualitas sistem. Silakan coba lagi nanti!
        </p>
        <a href="{{ route('game.dashboard') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
