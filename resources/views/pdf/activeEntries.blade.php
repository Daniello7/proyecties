<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Active Entries') }}</title>
    <style>
        body {
            margin-top: -30px;
            font-family: 'sans-serif';
        }

        #logo {
            float: left;
            margin: 10px;
            width: 100px;
            transform: translateY(10px);
        }

        table {
            border-collapse: collapse;
            border-radius: 10px;
            border: solid 2px #043f7e;
        }

        th {
            font-size: x-small;
            color: white;
            background: #043f7e;
            padding: 10px 5px;
            width: 16%;
        }

        td {
            font-size: small;
            padding: 5px;
            color: #042851;
        }

        tr:nth-child(odd) {
            background: #acdeff;
        }

        tr:nth-child(even) {
            background: #e7f5ff;
        }
    </style>
</head>
<body>
<header>
    <img id="logo" src="{{ public_path('images/logo.png') }}" width="100%" alt="logo.svg">
    <h3>PERSONAL EXTERNO EN INSTALACIONES</h3>
    <p>{{ now()->addHours(2)->format('H:i d/m/Y') }}</p>
    <p>Emitido por: {{ $user_id }}</p>
</header>
<hr>
<table>
    <tr>
        @foreach($columns as $col)
            <th>{{ strtoupper(__($col)) }}</th>
        @endforeach
    </tr>
    @foreach($entries as $entry)
        <tr>
            <td>{{ $entry->person->name.' '.$entry->person->last_name }}</td>
            <td>{{ $entry->person->company }}</td>
            <td>{{ $entry->internalPerson->person->name.' '.$entry->internalPerson->person->last_name }}</td>
            <td>{{ __($entry->reason) }}</td>
            <td>{{ \Carbon\Carbon::parse($entry->entry_time)->format('H:i d/m/Y') }}</td>
            <td>{{ $entry->comment }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>