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
    <h1>Normas para Transportistas</h1>
    <h2>Reglamento de Seguridad y Conducta</h2>
    <h4>DNI: {{ $person->document_number }}</h4>
    <h4>Transportista: {{ $person->name.' '.$person->last_name }}</h4>
    <p>Para garantizar la seguridad y el buen funcionamiento de nuestras instalaciones, los transportistas deben cumplir
        con las siguientes normas:</p>
    <ul class="rules">
        <li>Uso obligatorio de equipo de seguridad: Chaleco reflectante, botas de seguridad y casco.</li>
        <li>Respetar los límites de velocidad dentro de las instalaciones.</li>
        <li>Prohibido fumar en zonas no autorizadas.</li>
        <li>Mantener la higiene y el orden en las áreas de carga y descarga.</li>
        <li>No permitir pasajeros no autorizados en el vehículo.</li>
        <li>Seguir las indicaciones del personal de seguridad en todo momento.</li>
        <li>Evitar el uso del teléfono móvil mientras se conduce dentro de las instalaciones.</li>
        <li>Respetar los horarios establecidos para carga y descarga.</li>
        <li>Informar cualquier incidente o irregularidad inmediatamente a seguridad.</li>
    </ul>
    <p>El incumplimiento de estas normas puede resultar en la negación del acceso a las instalaciones.</p>
    <div class="date">
        <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
    <div class="signature">
        <p>Firma Transportista: {{ $person->name.' '.$person->last_name }}</p>
        <div class="signature-line"></div>
    </div>
</div>
</body>
</html>
