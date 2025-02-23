<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normas para Transportistas</title>
</head>
<style>
    header {
        float: left;
        margin: 10px;
        width: 40px;
    }

    .signature {
        float: left;
        border-bottom: solid 1px black;
        padding-bottom: 60px;
    }
</style>
<body>
<div class="container">
    <header id="header">
        <img src="{{ public_path('images/logo.png') }}" width="100%" alt="logo.svg">
    </header>
    <h1>Normas para Personal de Limpieza</h1>
    <h2>Reglamento de Higiene y Seguridad</h2>
    <h4>DNI: {{ $person->document_number }}</h4>
    <h4>Empleado: {{ $person->name.' '.$person->last_name }}</h4>
    <p>Para garantizar la seguridad y el buen funcionamiento de nuestras instalaciones, los transportistas deben cumplir
        con las siguientes normas:</p>
    <ul class="rules">
        <li>Uso obligatorio de equipo de protección: Guantes, mascarilla y uniforme adecuado.</li>
        <li>Disposición correcta de residuos: Separar basura orgánica e inorgánica según indicaciones.</li>
        <li>Uso adecuado de productos químicos: Leer etiquetas y seguir instrucciones de seguridad.</li>
        <li>Prohibido el uso de dispositivos móviles durante la jornada laboral.</li>
        <li>Mantener los pisos secos para evitar accidentes por resbalones.</li>
        <li>Respetar las áreas asignadas y evitar interferencias en zonas de trabajo.</li>
        <li>Informar de cualquier incidente o irregularidad al supervisor.</li>
        <li>Prohibido fumar y consumir alimentos en las áreas de limpieza.</li>
    </ul>
    <p>El incumplimiento de estas normas puede resultar en la negación del acceso a las instalaciones.</p>
    <div class="date">
        <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
    <div class="signature">
        <p>Firma Empleado: {{ $person->name.' '.$person->last_name }}</p>
        <div class="signature-line"></div>
    </div>
</div>
</body>
</html>
