<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normas para Limpieza</title>
</head>
<style>
    h3{
        text-transform: uppercase;
    }

    #logo {
        float: left;
        margin: 10px;
        width: 100px;
        transform: translateY(10px);
    }

    .date {
        float: right;
    }

    .signature {
        float: left;
    }

    .signature-line {
        width: 200px;
        border: solid 1px black;
        height: 80px;
    }

    .rules li {
        margin-bottom: 10px;
    }

    .health-condition {
        text-align: center;
    }

    .health-condition label {
        margin-right: 10px;
    }

    .checkbox-box {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid black;
        margin-left: 5px;
        margin-right: 10px;
        vertical-align: middle;
    }

    fieldset {
        margin-top: 30px;
        border-radius: 5px;
        border-color: #1d4ed8;
    }

    legend {
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td, th {
        border: solid #1d4ed8 1px;
        padding: 5px;
        text-align: center;
    }

    #rules-name {
        position: absolute;
        top: -50px;
        left: -30px;
    }

    #pages {
        position: absolute;
        bottom: 0;
        left: 0;
    }
</style>
<body>
<div class="container">
    <header id="header">
        <p id="rules-name">NORMAS LIMPIEZA</p>
        <img id="logo" src="{{ public_path('images/logo.png') }}" width="100%" alt="logo.svg">
        <h1>NORMAS DE LIMPIEZA</h1>
        <h2>Reglamento de Higiene y Seguridad</h2>
    </header>
    <hr>
    <h3>{{(__('Information'))}}</h3>
    <table>
        <tr>
            <th>NOMBRE</th>
            <th>DNI</th>
            <th>EMPRESA</th>
        </tr>
        <tr>
            <td>{{ $person->name.' '.$person->last_name }}</td>
            <td>{{ $person->document_number }}</td>
            <td>{{ $person->company }}</td>
        </tr>
    </table>
    <fieldset>
        <legend>REGLAS</legend>
        <p><b>Para garantizar la seguridad y el buen funcionamiento de nuestras instalaciones, los transportistas deben
                cumplir con las siguientes normas:</b></p>
        <ol class="rules">
            <li>Uso obligatorio de equipo de protección: Guantes, mascarilla y uniforme adecuado.</li>
            <li>Disposición correcta de residuos: Separar basura orgánica e inorgánica según indicaciones.</li>
            <li>Uso adecuado de productos químicos: Leer etiquetas y seguir instrucciones de seguridad.</li>
            <li>Prohibido el uso de dispositivos móviles durante la jornada laboral.</li>
            <li>Mantener los pisos secos para evitar accidentes por resbalones.</li>
            <li>Respetar las áreas asignadas y evitar interferencias en zonas de trabajo.</li>
            <li>Informar de cualquier incidente o irregularidad al supervisor.</li>
            <li>Prohibido fumar y consumir alimentos en las áreas de limpieza.</li>
        </ol>
    </fieldset>
    <p><b>El incumplimiento de estas normas puede resultar en la negación del acceso a las
            instalaciones.</b></p>
    <hr>
    <div> En caso de presentar alguna condición médica o enfermedad que pueda representar un riesgo para la salud
        propia o de otras personas dentro de las instalaciones, el personal deberá notificarlo previamente al ingreso.
        Esta información permitirá tomar las medidas preventivas correspondientes para garantizar un entorno seguro para
        todos.
        <div class="health-condition">
            <label> Sí <span class="checkbox-box"></span> </label> <label> No <span class="checkbox-box"></span>
            </label>
        </div>
    </div>
    <footer>
        <div class="signature">
            <p>Firma: {{ $person->name.' '.$person->last_name }}</p>
            <div class="signature-line"></div>
        </div>
        <div class="date">
            <p>Fecha: {{ \Carbon\Carbon::now()->addHour(2)->format('d/m/Y, H:i') }}</p>
        </div>
        <p id="pages">Hoja 1 de 1</p>
    </footer>
</div>
</body>
</html>
