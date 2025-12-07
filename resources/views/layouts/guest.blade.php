<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Manajemen Mess</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(180deg, #F7F7F7 0%, #FFC107 50%, #000000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 500px;
            padding: 15px;
        }

        .card {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            border-radius: 12px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FFC107 0%, #FFD700 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            color: #000000 !important;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(255, 193, 7, 0.4);
            background: linear-gradient(135deg, #FFD700 0%, #FFC107 100%);
        }

        .btn-outline-secondary {
            border-radius: 8px;
            font-weight: 600;
            color: #000000 !important;
            border-color: #000000 !important;
            font-family: 'Poppins', sans-serif;
        }

        .btn-outline-secondary:hover {
            background-color: #000000;
            color: #FFC107 !important;
        }

        .btn-outline-primary {
            border-radius: 8px;
            font-weight: 600;
            color: #FFC107 !important;
            border-color: #FFC107 !important;
            font-family: 'Poppins', sans-serif;
        }

        .btn-outline-primary:hover {
            background-color: #FFC107;
            color: #000000 !important;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            border-color: #FFC107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-select:focus {
            border-color: #FFC107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .form-select-lg {
            padding: 12px 16px;
            font-size: 16px;
        }

        .invalid-feedback {
            font-size: 13px;
            margin-top: 5px;
            display: block;
            font-family: 'Poppins', sans-serif;
        }

        .form-label {
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }

        .text-primary {
            color: #FFC107 !important;
        }

        .text-dark {
            color: #000000 !important;
        }

        .text-muted {
            color: #666666 !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #000000;
        }

        hr {
            margin: 20px 0;
            border: 0;
            border-top: 1px solid #eee;
        }

        .alert {
            border-radius: 8px;
            border: none;
            font-family: 'Poppins', sans-serif;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-info {
            background-color: #FFF3CD;
            color: #000000;
            border: 1px solid #FFE69C;
        }

        .form-check-input {
            border-radius: 4px;
            width: 1.2em;
            height: 1.2em;
            border: 2px solid #000000;
            font-family: 'Poppins', sans-serif;
        }

        .form-check-input:checked {
            background-color: #FFC107;
            border-color: #FFC107;
        }

        .form-check-label {
            font-family: 'Poppins', sans-serif;
            color: #000000;
        }

        code {
            background-color: #F5F5F5;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #000000;
            font-family: 'Courier New', monospace;
        }

        .card-title {
            color: #000000;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem !important;
            }

            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
