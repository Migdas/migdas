{{-- Regulamin jako samodzielny plik HTML - załącznik do potwierdzenia zamówienia. --}}
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">

    <title>Regulamin sklepu internetowego Migdas</title>

    <style>
        body {
            max-width: 820px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #181818;
        }

        h2 {
            margin-top: 32px;
        }

        li {
            margin-bottom: 6px;
        }

        .legal-meta {
            color: #777;
        }

        .legal-todo {
            background: #fff3b0;
        }

        .legal-form {
            border: 1px dashed #ccc;
            padding: 16px 20px;
        }
    </style>
</head>

<body>

@include('store.partials.terms-content')

</body>
</html>
