<h1>Accès validé</h1>

@if($barcode)
    <p>Présentez ce QR à l’accueil</p>

    <img
        src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $barcode }}"
        alt="QR Code"
    />
@else
    <p style="color:red">
        QR code indisponible
    </p>
@endif