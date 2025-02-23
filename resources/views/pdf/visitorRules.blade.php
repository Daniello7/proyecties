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
        <img src="{{ public_path('images/logo.png') }}" width="100%" alt="logo.png">
    </header>
    <h1>Normas para Visitantes</h1>
    <h2>Reglamento de Seguridad en la Fábrica</h2>
    <h4>DNI: {{ $person->document_number }}</h4>
    <h4>Visita: {{ $person->name.' '.$person->last_name }}</h4>
    <p>Para garantizar la seguridad de todos, los visitantes deben cumplir con las siguientes normas:</p>
    <ul class="rules">
        <li>Uso obligatorio de equipo de seguridad: Casco, chaleco y gafas de protección si es necesario.</li>
        <li>Prohibido el acceso a áreas restringidas sin autorización del personal.</li>
        <li>No se permite tomar fotos o videos sin autorización.</li>
        <li>Respetar las señales de seguridad y las instrucciones del personal.</li>
        <li>No interferir con los procesos de producción ni tocar maquinaria.</li>
        <li>Prohibido fumar y consumir alimentos en las instalaciones.</li>
        <li>En caso de emergencia, seguir las instrucciones del personal y dirigirse a los puntos de reunión.</li>
        <li>Todo visitante debe estar acompañado por un responsable de la empresa en todo momento.</li>
    </ul>
    <p>Al firmar este documento, el visitante se compromete a respetar todas las normas establecidas.</p>
    <div class="date">
        <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
    <div class="signature">
        <p>Firma Visitante: {{ $person->name.' '.$person->last_name }}</p>
        <div class="signature-line"></div>
    </div>
</div>
</body>
</html>
