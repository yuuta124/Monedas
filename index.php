<?php
// tienda2.php
session_start();

function getProducts() {
    return [
        ['id' => 1, 'nombre' => '10M Monedas',  'precio_soles' => 1,  'precio_usd' => 0.27, 'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png', 'promo' => '¡Mini Pack!'],
        ['id' => 2, 'nombre' => '22M Monedas',  'precio_soles' => 2,  'precio_usd' => 0.54, 'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135789.png', 'promo' => '¡Oferta!'],
        ['id' => 3, 'nombre' => '34M Monedas',  'precio_soles' => 3,  'precio_usd' => 0.81, 'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135768.png', 'promo' => '🔥 Pack'],
        ['id' => 4, 'nombre' => '60M Monedas',  'precio_soles' => 5,  'precio_usd' => 1.35, 'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135778.png', 'promo' => '🎁 Super Pack'],
        ['id' => 5, 'nombre' => '90M Monedas',  'precio_soles' => 7,  'precio_usd' => 1.89, 'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135787.png', 'promo' => '⭐ Mega Oferta'],
        ['id' => 6, 'nombre' => '120M Monedas', 'precio_soles' => 10, 'precio_usd' => 2.70, 'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png', 'promo' => '💎 Ultra Pack'],
        ['id' => 7, 'nombre' => '240M Monedas', 'precio_soles' => 20, 'precio_usd' => 5.40, 'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135789.png', 'promo' => '🚀 Pack Gigante'],
    ];
}

function getPromos() {
    return [
        [
            'titulo' => '🔥 Pack del Mes',
            'desc' => '¡Llévate el pack de 60M con 10% extra solo este mes!',
            'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135778.png',
            'color' => 'linear-gradient(90deg,#fbbf24 60%,#38bdf8 100%)'
        ],
        [
            'titulo' => '🎁 Sorteo Semanal',
            'desc' => 'Participa gratis por cada compra y gana hasta 100M monedas.',
            'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135787.png',
            'color' => 'linear-gradient(90deg,#38bdf8 60%,#fbbf24 100%)'
        ],
        [
            'titulo' => '💸 Descuento Flash',
            'desc' => '¡Solo hoy! 15% de descuento en el pack de 120M.',
            'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
            'color' => 'linear-gradient(90deg,#f472b6 60%,#38bdf8 100%)'
        ],
        [
            'titulo' => '🚀 Invita y Gana',
            'desc' => 'Por cada amigo que compre, recibes 5M gratis.',
            'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135789.png',
            'color' => 'linear-gradient(90deg,#34d399 60%,#fbbf24 100%)'
        ],
    ];
}

function getFreeCoinsLinks() {
    return [
        [
            'id' => 1,
            'titulo' => '¡Consigue 2M Monedas Gratis Rápido!',
            'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
            'url' => 'https://linkvertise.com/1336558/a7hhVIclTCel?o=sharing',
            'desc' => 'Haz clic, sigue los pasos y obtén tus monedas gratis en minutos. ¡Sin límites, invita a tus amigos y gana más!'
        ],
        [
            'id' => 2,
            'titulo' => '¡Obtén 5M Monedas Gratis YA!',
            'img' => 'https://cdn-icons-png.flaticon.com/512/3135/3135789.png',
            'url' => 'https://www.mediafire.com/view/cl4mux3f9j2x3ht/Moneda_5M.png/file',
            'desc' => 'Accede al link, completa la acción y recibe tus monedas gratis. ¡Entre más participes, más ganas!'
        ],
    ];
}

define('WHATSAPP_NUM', '51916477262');
define('WHATSAPP_LINK', 'https://wa.me/' . WHATSAPP_NUM . '?text=' . urlencode("Hola, subo mi comprobante de pago para SkyBlock. Mi correo es: "));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
    $producto = getProducts()[$_POST['producto_id']-1];
    $pago = $_POST['pago'] ?? '';
    $correo = trim($_POST['correo'] ?? '');
    $fecha = date('Y-m-d H:i:s');
    $reporte = [
        'producto' => $producto['nombre'],
        'precio_soles' => $producto['precio_soles'],
        'precio_usd' => $producto['precio_usd'],
        'pago' => $pago,
        'correo' => $correo,
        'fecha' => $fecha,
        'estado' => 'Esperando confirmación de pago',
        'id' => uniqid('ORD-')
    ];
    $_SESSION['reporte'] = $reporte;
    header('Location: ?confirmacion=1');
    exit;
}

if (isset($_GET['descargar_factura'])) {
    $reporte = $_SESSION['reporte'] ?? null;
    if ($reporte) {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="boleto_compra.txt"');
        $boleto = "
╔══════════════════════════════════════╗
║         🎟️  BOLETO DE COMPRA        ║
╠══════════════════════════════════════╣
║  Producto: {$reporte['producto']}
║  Precio:   S/{$reporte['precio_soles']} (USD {$reporte['precio_usd']})
║  Método:   {$reporte['pago']}
║  Correo:   {$reporte['correo']}
║  Fecha:    {$reporte['fecha']}
║  Estado:   {$reporte['estado']}
║  Orden:    {$reporte['id']}
╠══════════════════════════════════════╣
║   ¡Gracias por tu compra en SkyBlock!  ║
║   Guarda este boleto como comprobante  ║
╚══════════════════════════════════════╝
";
        echo $boleto;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda SkyBlock - Venta de Monedas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* ... (todo tu CSS anterior) ... */
        :root {
            --main: #38bdf8;
            --accent: #fbbf24;
            --dark: #1e293b;
            --light: #f1f5f9;
        }
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--dark) 0%, #64748b 100%);
            color: #fff;
            min-height: 100vh;
        }
        header {
            background: #0f172a;
            padding: 20px 0 0 0;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            position: sticky; top: 0; z-index: 100;
            animation: slideDown 1s;
        }
        @keyframes slideDown { from {transform: translateY(-80px);} to {transform: none;} }
        header h1 {
            margin: 0;
            font-size: 2.5rem;
            letter-spacing: 2px;
            animation: fadeIn 1.2s;
        }
        nav {
            margin-top: 10px;
            background: #1e293b;
            padding: 10px 0;
            border-radius: 0 0 18px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            animation: fadeIn 1.5s;
        }
        nav a {
            color: var(--main);
            text-decoration: none;
            margin: 0 15px;
            font-weight: 700;
            transition: color 0.2s, transform 0.2s;
            font-size: 1.1rem;
            padding: 6px 14px;
            border-radius: 8px;
        }
        nav a:hover, nav a.active {
            color: var(--accent);
            background: #334155;
            transform: scale(1.08);
        }
        .promos-bar {
            background: linear-gradient(90deg,var(--accent),var(--main),var(--accent),var(--main));
            color: #1e293b;
            font-weight: bold;
            text-align: center;
            padding: 10px 0;
            font-size: 1.1rem;
            animation: fadeIn 2s;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .promos-bar .emojis {
            font-size: 1.3em;
            margin-right: 8px;
            animation: bounceEmojis 2s infinite alternate;
        }
        @keyframes bounceEmojis {
            0% { transform: translateY(0);}
            100% { transform: translateY(-6px);}
        }
        /* Slider */
        .slider {
            position: relative;
            max-width: 900px;
            margin: 40px auto 20px auto;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.3);
            animation: fadeInUp 1.2s;
        }
        .slides {
            display: flex;
            transition: transform 0.7s cubic-bezier(.77,0,.18,1);
        }
        .slide {
            min-width: 100%;
            box-sizing: border-box;
            background-size: cover;
            background-position: center;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            text-shadow: 0 2px 8px #000;
            animation: fadeIn 1.5s;
            position: relative;
        }
        .slide .slide-content {
            background: rgba(30,41,59,0.7);
            padding: 18px 30px;
            border-radius: 16px;
            box-shadow: 0 2px 8px #0004;
            font-size: 1.3em;
            animation: popIn 1s;
        }
        @keyframes popIn {
            0% { transform: scale(0.8);}
            100% { transform: scale(1);}
        }
        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(30,41,59,0.7);
            border: none;
            color: #fff;
            font-size: 2rem;
            padding: 10px 18px;
            cursor: pointer;
            border-radius: 50%;
            z-index: 2;
            transition: background 0.2s;
        }
        .slider-btn:hover {
            background: var(--main);
        }
        .slider-btn.prev { left: 10px; }
        .slider-btn.next { right: 10px; }
        /* Productos */
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 40px auto;
            max-width: 1100px;
        }
        .product-card {
            background: #334155;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.18);
            padding: 28px 22px;
            width: 300px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            animation: fadeInUp 1.2s;
        }
        .product-card:hover {
            transform: translateY(-8px) scale(1.03) rotate(-1deg);
            box-shadow: 0 8px 32px rgba(56,189,248,0.18);
        }
        .product-card .promo {
            position: absolute;
            top: 18px;
            right: -40px;
            background: var(--accent);
            color: #1e293b;
            font-weight: bold;
            padding: 6px 40px;
            transform: rotate(25deg);
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            animation: promoAnim 1.5s infinite alternate;
        }
        @keyframes promoAnim {
            0% { box-shadow: 0 2px 8px #fbbf2440;}
            100% { box-shadow: 0 4px 16px #fbbf2480;}
        }
        .product-card img {
            width: 80px; height: 80px; margin-bottom: 10px; border-radius: 50%; background: #fff;
            box-shadow: 0 2px 8px rgba(56,189,248,0.12);
            animation: bounceIn 1.2s;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.7);}
            60% { transform: scale(1.15);}
            100% { transform: scale(1);}
        }
        .product-card h2 {
            margin: 18px 0 8px 0;
            font-size: 1.5rem;
        }
        .product-card .price {
            font-size: 1.3rem;
            color: var(--main);
            margin-bottom: 10px;
        }
        .product-card .old-price {
            text-decoration: line-through;
            color: #f87171;
            font-size: 1rem;
            margin-left: 8px;
        }
        .product-card button {
            background: linear-gradient(90deg, var(--main) 0%, var(--accent) 100%);
            color: #1e293b;
            border: none;
            border-radius: 8px;
            padding: 10px 28px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 12px;
            transition: background 0.2s, color 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(56,189,248,0.10);
        }
        .product-card button:hover {
            background: var(--accent);
            color: #334155;
            transform: scale(1.07);
        }
        /* Animaciones fade-in */
        .fade-in { opacity: 0; transform: translateY(30px); animation: fadeInUp 1s forwards; }
        @keyframes fadeInUp { to { opacity: 1; transform: none; } }
        @keyframes fadeIn { from { opacity: 0;} to { opacity: 1;} }
        /* Modal */
        .modal-bg {
            position: fixed; top:0; left:0; width:100vw; height:100vh;
            background: rgba(0,0,0,0.6); display: none; align-items: center; justify-content: center; z-index: 1000;
            animation: fadeIn 0.5s;
        }
        .modal {
            background: #fff; color: #222; border-radius: 16px; padding: 32px 24px; max-width: 420px; width: 95vw; box-shadow: 0 4px 24px rgba(0,0,0,0.25);
            position: relative;
            animation: fadeInUp 0.7s;
        }
        .modal h2 { margin-top: 0; }
        .modal label { font-weight: bold; display: block; margin-top: 16px; }
        .modal input[type="email"], .modal input[type="text"] { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 4px; }
        .modal input[type="file"] { margin-top: 8px; }
        .modal .pago-info { background: #f1f5f9; padding: 10px; border-radius: 8px; margin-top: 10px; color: #222; }
        .modal .close-btn { position: absolute; top: 10px; right: 16px; background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #888; }
        .modal button[type="submit"] { margin-top: 18px; background: var(--main); color: #fff; border: none; border-radius: 8px; padding: 10px 28px; font-size: 1.1rem; font-weight: bold; cursor: pointer; }
        .modal button[type="submit"]:hover { background: #0ea5e9; }
        .factura-link { display: inline-block; margin-top: 18px; background: var(--accent); color: #1e293b; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .factura-link:hover { background: #fde68a; }
        .pay-icons {
            display: flex; gap: 18px; justify-content: center; margin: 18px 0 10px 0;
        }
        .pay-icon-btn {
            background: #f1f5f9;
            border: 2px solid var(--main);
            border-radius: 12px;
            padding: 12px 18px;
            cursor: pointer;
            transition: background 0.2s, border 0.2s, transform 0.2s;
            font-size: 1.7rem;
            color: var(--main);
            display: flex; flex-direction: column; align-items: center;
            min-width: 70px;
        }
        .pay-icon-btn.selected, .pay-icon-btn:hover {
            background: var(--main);
            color: #fff;
            border: 2px solid var(--accent);
            transform: scale(1.08);
        }
        .pay-icon-btn span {
            font-size: 0.9rem;
            margin-top: 4px;
            color: #222;
        }
        .pay-icon-btn.selected span { color: #fff; }
        .qr-box {
            display: flex; flex-direction: column; align-items: center; margin: 10px 0;
        }
        .qr-box img { border-radius: 12px; box-shadow: 0 2px 8px var(--main); margin-bottom: 6px;}
        .qr-box small { color: #222; }
        /* Modal stepper */
        .modal-step { display: none; }
        .modal-step.active { display: block; }
        .stepper-btn {
            background: var(--main);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 28px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 18px;
        }
        .stepper-btn:hover { background: #0ea5e9; }
        .whatsapp-link {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(90deg,#25d366 60%,#128c7e 100%);
            color: #fff;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: bold;
            margin-top: 16px;
            text-decoration: none;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px #25d36640;
            gap: 10px;
            transition: background 0.2s, transform 0.2s;
            border: none;
        }
        .whatsapp-link:hover { background: #128c7e; transform: scale(1.04);}
        .whatsapp-link .wa-badge {
            background: #fff;
            color: #25d366;
            border-radius: 50%;
            padding: 6px 8px;
            font-size: 1.2em;
            margin-right: 8px;
            box-shadow: 0 1px 4px #25d36630;
        }
        .whatsapp-link .wa-text {
            font-size: 1.05em;
        }
        /* Nueva sección de monedas gratis */
        .free-coins-section {
            background: #fff;
            color: #1e293b;
            border-radius: 18px;
            max-width: 900px;
            margin: 40px auto 0 auto;
            box-shadow: 0 4px 24px #0002;
            padding: 32px 18px 38px 18px;
            animation: fadeIn 1.2s;
        }
        .free-coins-section h2 {
            color: var(--main);
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 2rem;
            text-align: center;
        }
        .free-coins-slider {
            position: relative;
            max-width: 700px;
            margin: 0 auto;
            overflow: hidden;
        }
        .free-coins-slides {
            display: flex;
            transition: transform 0.7s cubic-bezier(.77,0,.18,1);
        }
        .free-coins-slide {
            min-width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 30px 10px;
            background: linear-gradient(120deg,#fbbf24 0%,#38bdf8 100%);
            border-radius: 16px;
            box-shadow: 0 2px 12px #0001;
            margin: 0 10px;
            text-align: center;
        }
        .free-coins-slide img {
            width: 80px; height: 80px; border-radius: 50%; background: #fff; margin-bottom: 12px; box-shadow: 0 2px 8px #38bdf820;
        }
        .free-coins-slide h3 {
            margin: 0 0 8px 0;
            color: #1e293b;
            font-size: 1.3rem;
        }
        .free-coins-slide p {
            color: #334155;
            font-size: 1.05rem;
            margin-bottom: 14px;
        }
        .free-coins-slide a {
            background: #38bdf8;
            color: #fff;
            padding: 10px 26px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px #38bdf820;
        }
        .free-coins-slide a:hover {
            background: #0ea5e9;
            transform: scale(1.06);
        }
        .free-coins-slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            color: #38bdf8;
            border: none;
            font-size: 2rem;
            padding: 8px 14px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px #38bdf820;
            z-index: 2;
            transition: background 0.2s, color 0.2s;
        }
        .free-coins-slider-btn:hover {
            background: #38bdf8;
            color: #fff;
        }
        .free-coins-slider-btn.prev { left: 10px; }
        .free-coins-slider-btn.next { right: 10px; }
        .free-coins-section .desc {
            text-align: center;
            color: #475569;
            font-size: 1.08rem;
            margin-bottom: 18px;
        }
        @media (max-width: 900px) {
            .products, .features {
                flex-direction: column;
                align-items: center;
            }
            .slider, .free-coins-section { max-width: 98vw; }
        }
        @media (max-width: 600px) {
            header h1 { font-size: 1.5rem; }
            .product-card, .feature, .extra-content, .free-coins-section { width: 95vw; }
            .slide { font-size: 1.2rem; }
        }
        /* Características y extras */
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 40px auto 60px auto;
            max-width: 1100px;
        }
        .feature {
            background: #475569;
            border-radius: 14px;
            padding: 24px 18px;
            width: 260px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            animation: fadeInUp 1.5s;
        }
        .feature i {
            font-size: 2.2rem;
            color: var(--main);
            margin-bottom: 10px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { color: var(--main);}
            50% { color: var(--accent);}
        }
        .feature h3 {
            margin: 10px 0 6px 0;
            font-size: 1.2rem;
        }
        .feature p {
            font-size: 1rem;
            color: #cbd5e1;
        }
        .extra-content {
            background: #1e293b;
            margin: 40px auto;
            max-width: 900px;
            border-radius: 18px;
            padding: 30px 20px;
            box-shadow: 0 2px 12px rgba(56,189,248,0.08);
            text-align: center;
            animation: fadeIn 2s;
        }
        .extra-content h2 { color: var(--accent); }
        .extra-content ul {
            list-style: none;
            padding: 0;
            color: #fff;
            font-size: 1.1rem;
        }
        .extra-content ul li {
            margin: 12px 0;
            position: relative;
            padding-left: 28px;
        }
        .extra-content ul li:before {
            content: "★";
            color: var(--main);
            position: absolute;
            left: 0;
        }
        footer {
            background: #0f172a;
            text-align: center;
            padding: 18px 0;
            color: #94a3b8;
            font-size: 1rem;
            margin-top: 40px;
            animation: fadeIn 2s;
        }
        /* Solo agrego los cambios y el nuevo estilo para la sección de monedas gratis al final */
        .free-coins-section {
            background: linear-gradient(120deg,#38bdf8 0%,#fbbf24 100%);
            color: #1e293b;
            border-radius: 22px;
            max-width: 900px;
            margin: 60px auto 30px auto;
            box-shadow: 0 8px 32px #0002;
            padding: 38px 18px 44px 18px;
            animation: fadeIn 1.2s;
            position: relative;
            overflow: hidden;
        }
        .free-coins-section:before {
            content: "";
            position: absolute;
            top: -60px; right: -60px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, #fbbf24 60%, transparent 100%);
            opacity: 0.25;
            z-index: 0;
        }
        .free-coins-section h2 {
            color: #fff;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 2.2rem;
            text-align: center;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #1e293b80;
        }
        .free-coins-slider {
            position: relative;
            max-width: 700px;
            margin: 0 auto;
            overflow: hidden;
        }
        .free-coins-slides {
            display: flex;
            transition: transform 0.7s cubic-bezier(.77,0,.18,1);
        }
        .free-coins-slide {
            min-width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 38px 10px 30px 10px;
            background: rgba(255,255,255,0.85);
            border-radius: 18px;
            box-shadow: 0 4px 24px #0001;
            margin: 0 10px;
            text-align: center;
            position: relative;
        }
        .free-coins-slide img {
            width: 90px; height: 90px; border-radius: 50%; background: #fff; margin-bottom: 16px; box-shadow: 0 2px 12px #38bdf820;
            border: 4px solid #38bdf8;
        }
        .free-coins-slide h3 {
            margin: 0 0 10px 0;
            color: #1e293b;
            font-size: 1.4rem;
            font-weight: bold;
        }
        .free-coins-slide p {
            color: #334155;
            font-size: 1.08rem;
            margin-bottom: 16px;
        }
        .free-coins-slide a {
            background: linear-gradient(90deg,#38bdf8 60%,#fbbf24 100%);
            color: #fff;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.15rem;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 2px 12px #38bdf820;
            letter-spacing: 1px;
        }
        .free-coins-slide a:hover {
            background: #0ea5e9;
            transform: scale(1.07);
        }
        .free-coins-slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            color: #38bdf8;
            border: none;
            font-size: 2rem;
            padding: 10px 16px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px #38bdf820;
            z-index: 2;
            transition: background 0.2s, color 0.2s;
        }
        .free-coins-slider-btn:hover {
            background: #38bdf8;
            color: #fff;
        }
        .free-coins-slider-btn.prev { left: 10px; }
        .free-coins-slider-btn.next { right: 10px; }
        .free-coins-section .desc {
            text-align: center;
            color: #fff;
            font-size: 1.13rem;
            margin-bottom: 22px;
            text-shadow: 0 2px 8px #1e293b60;
        }
        @media (max-width: 900px) {
            .free-coins-section { max-width: 98vw; }
        }
        @media (max-width: 600px) {
            .free-coins-section { width: 97vw; }
            .free-coins-slide img { width: 70px; height: 70px; }
        }
        /* Promos Slider Nuevo */
        .promos-slider-section {
            background: linear-gradient(120deg,#38bdf8 0%,#fbbf24 100%);
            padding: 38px 0 44px 0;
            border-radius: 22px;
            max-width: 900px;
            margin: 40px auto 0 auto;
            box-shadow: 0 8px 32px #0002;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1.2s;
        }
        .promos-slider-title {
            color: #fff;
            font-size: 2.1rem;
            text-align: center;
            margin-bottom: 18px;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #1e293b80;
        }
        .promos-slider {
            position: relative;
            max-width: 700px;
            margin: 0 auto;
            overflow: hidden;
        }
        .promos-slides {
            display: flex;
            transition: transform 0.7s cubic-bezier(.77,0,.18,1);
        }
        .promo-slide {
            min-width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
            padding: 38px 10px 30px 10px;
            border-radius: 18px;
            box-shadow: 0 4px 24px #0001;
            margin: 0 10px;
            text-align: left;
            position: relative;
            background: #fff;
            overflow: hidden;
        }
        .promo-slide .promo-img {
            width: 90px; height: 90px; border-radius: 50%; background: #fff; margin-right: 28px; box-shadow: 0 2px 12px #38bdf820;
            border: 4px solid #38bdf8;
            flex-shrink: 0;
        }
        .promo-slide .promo-content {
            flex: 1;
        }
        .promo-slide .promo-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .promo-slide .promo-desc {
            color: #334155;
            font-size: 1.08rem;
            margin-bottom: 10px;
        }
        .promo-slide .promo-badge {
            display: inline-block;
            background: #fbbf24;
            color: #1e293b;
            font-weight: bold;
            padding: 6px 18px;
            border-radius: 8px;
            font-size: 1rem;
            margin-top: 8px;
            box-shadow: 0 2px 8px #fbbf2440;
        }
        .promos-slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            color: #38bdf8;
            border: none;
            font-size: 2rem;
            padding: 10px 16px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px #38bdf820;
            z-index: 2;
            transition: background 0.2s, color 0.2s;
        }
        .promos-slider-btn:hover {
            background: #38bdf8;
            color: #fff;
        }
        .promos-slider-btn.prev { left: 10px; }
        .promos-slider-btn.next { right: 10px; }
        @media (max-width: 700px) {
            .promo-slide { flex-direction: column; text-align: center; }
            .promo-slide .promo-img { margin: 0 0 18px 0; }
        }
        /* ... resto de tu CSS ... */
    </style>
</head>
<body>
    <header>
        <h1><i class="fa-solid fa-coins"></i> Tienda SkyBlock Monedas</h1>
        <nav>
            <a href="#productos" class="active"><i class="fa-solid fa-store"></i> Productos</a>
            <a href="#promos"><i class="fa-solid fa-bolt"></i> Promociones</a>
            <a href="#caracteristicas"><i class="fa-solid fa-star"></i> ¿Por qué elegirnos?</a>
            <a href="#extra"><i class="fa-solid fa-gift"></i> Beneficios</a>
            <a href="#soporte"><i class="fa-solid fa-headset"></i> Soporte</a>
            <a href="#monedasgratis"><i class="fa-solid fa-gift"></i> Monedas Gratis</a>
        </nav>
    </header>

    <div class="promos-bar">
        <span class="emojis">🎉💸🔥🎁</span>
        <marquee behavior="scroll" direction="left" scrollamount="7">
            🚀 Recarga monedas al mejor precio: S/1 = 10M, S/2 = 22M, S/3 = 34M, S/5 = 60M, S/7 = 90M, S/10 = 120M, S/20 = 240M. ¡Entre más recargas, más ganas! | 🎁 Promos y sorteos cada semana | 💸 Entrega inmediata | 🏆 ¡Compra y participa en sorteos mensuales!
        </marquee>
    </div>

    <!-- Promos Slider Mejorado -->
    <section class="promos-slider-section fade-in" id="promos">
        <div class="promos-slider-title"><i class="fa-solid fa-bolt"></i> Promociones Especiales</div>
        <div class="promos-slider">
            <button class="promos-slider-btn prev" onclick="movePromoSlide(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="promos-slides" id="promos-slides">
                <?php foreach(getPromos() as $promo): ?>
                <div class="promo-slide" style="background:<?=$promo['color']?>;">
                    <img class="promo-img" src="<?=htmlspecialchars($promo['img'])?>" alt="Promo">
                    <div class="promo-content">
                        <div class="promo-title"><?=htmlspecialchars($promo['titulo'])?></div>
                        <div class="promo-desc"><?=htmlspecialchars($promo['desc'])?></div>
                        <div class="promo-badge">¡Aprovecha ahora!</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="promos-slider-btn next" onclick="movePromoSlide(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Productos -->
    <section class="products fade-in" id="productos">
        <?php foreach(getProducts() as $i => $p): ?>
        <div class="product-card">
            <?php if($p['promo']): ?><div class="promo"><?=htmlspecialchars($p['promo'])?></div><?php endif; ?>
            <img src="<?=htmlspecialchars($p['img'])?>" alt="Producto">
            <h2><?=htmlspecialchars($p['nombre'])?></h2>
            <div class="price">
                S/<?=number_format($p['precio_soles'],2)?>
                <span class="usd-price">(USD <?=number_format($p['precio_usd'],2)?>)</span>
            </div>
            <button onclick="abrirModal(<?=$p['id']?>)">Comprar</button>
        </div>
        <?php endforeach; ?>
    </section>

    <section class="features fade-in" id="caracteristicas">
        <div class="feature">
            <i class="fa-solid fa-shield-halved"></i>
            <h3>Compra Segura</h3>
            <p>Pagos protegidos y entrega garantizada en minutos.</p>
        </div>
        <div class="feature">
            <i class="fa-solid fa-bolt"></i>
            <h3>Entrega Rápida</h3>
            <p>Recibe tus monedas en menos de 10 minutos tras el pago.</p>
        </div>
        <div class="feature">
            <i class="fa-solid fa-gift"></i>
            <h3>Promociones Semanales</h3>
            <p>Descuentos y packs especiales cada semana.</p>
        </div>
        <div class="feature">
            <i class="fa-solid fa-headset"></i>
            <h3>Soporte 24/7</h3>
            <p>Atención personalizada por WhatsApp y Discord.</p>
        </div>
    </section>

    <section class="extra-content fade-in" id="extra">
        <h2>🎁 Beneficios exclusivos para clientes SkyBlock</h2>
        <ul>
            <li>Participa en sorteos mensuales de monedas y premios.</li>
            <li>Descuentos especiales por compras recurrentes.</li>
            <li>Acceso a eventos VIP y promociones flash.</li>
            <li>Soporte prioritario y atención personalizada.</li>
            <li>¡Comparte tu compra en redes y recibe cupones!</li>
        </ul>
    </section>

    <section class="extra-content fade-in" id="soporte">
        <h2>¿Necesitas ayuda?</h2>
        <p>
            <i class="fa-brands fa-whatsapp"></i> <b>WhatsApp:</b> <a href="https://wa.me/<?=WHATSAPP_NUM?>" target="_blank">+51 916 477 262</a><br>
            <i class="fa-brands fa-discord"></i> <b>Discord:</b> <a href="https://discord.gg/skyblock" target="_blank">skyblock#1234</a><br>
            <i class="fa-solid fa-envelope"></i> <b>Email:</b> <a href="mailto:christhoperr246@gmail.com">christhoperr246@gmail.com</a>
        </p>
        <p>¡Estamos para ayudarte 24/7!</p>
    </section>

    <!-- Monedas Gratis -->
    <section class="free-coins-section fade-in" id="monedasgratis">
        <h2><i class="fa-solid fa-gift"></i> Monedas Gratis</h2>
        <div class="desc">
            <b>¡Obtén monedas gratis en minutos!</b><br>
            Solo sigue los pasos y recibe tus monedas. <br>
            <span style="color:#fff;background:#e11d48;padding:2px 10px;border-radius:8px;">¡Sin límites! Invita a tus amigos y gana más.</span>
        </div>
        <div class="free-coins-slider">
            <button class="free-coins-slider-btn prev" onclick="moveFreeCoinsSlide(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="free-coins-slides" id="free-coins-slides">
                <?php foreach(getFreeCoinsLinks() as $link): ?>
                <div class="free-coins-slide">
                    <img src="<?=htmlspecialchars($link['img'])?>" alt="Monedas Gratis">
                    <h3><?=htmlspecialchars($link['titulo'])?></h3>
                    <p><?=htmlspecialchars($link['desc'])?></p>
                    <a href="<?=htmlspecialchars($link['url'])?>" target="_blank"><i class="fa-solid fa-bolt"></i> Reclamar ahora</a>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="free-coins-slider-btn next" onclick="moveFreeCoinsSlide(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>

    <footer>
        &copy; <?=date('Y');?> Tienda SkyBlock Monedas. Todos los derechos reservados.<br>
        <span style="font-size:0.9em;">Sitio seguro <i class="fa-solid fa-lock"></i> | Síguenos en <a href="#" style="color:#38bdf8;">Instagram</a> y <a href="#" style="color:#38bdf8;">TikTok</a></span>
    </footer>

    <!-- Modal de compra con pasos -->
    <div class="modal-bg" id="modal-bg">
        <form class="modal" id="form-compra" method="post" autocomplete="off">
            <button type="button" class="close-btn" onclick="cerrarModal()">&times;</button>
            <input type="hidden" name="producto_id" id="producto_id" value="">
            <!-- Paso 1: Selección de método de pago -->
            <div class="modal-step" id="step1">
                <h2>Selecciona método de pago</h2>
                <div class="pay-icons">
                    <div class="pay-icon-btn" data-pay="Yape" onclick="selectPay(this)">
                        <i class="fa-brands fa-y-combinator"></i>
                        <span>Yape</span>
                    </div>
                    <div class="pay-icon-btn" data-pay="Plin" onclick="selectPay(this)">
                        <i class="fa-solid fa-mobile-screen"></i>
                        <span>Plin</span>
                    </div>
                    <div class="pay-icon-btn" data-pay="Paypal" onclick="selectPay(this)">
                        <i class="fa-brands fa-paypal"></i>
                        <span>Paypal</span>
                    </div>
                    <div class="pay-icon-btn" data-pay="Banco" onclick="selectPay(this)">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Banco</span>
                    </div>
                </div>
                <input type="hidden" name="pago" id="pago" required>
                <button type="button" class="stepper-btn" id="btn-continuar" onclick="continuarPaso2()" disabled>Continuar</button>
            </div>
            <!-- Paso 2: Detalles y enviar a WhatsApp -->
            <div class="modal-step" id="step2">
                <h2>Detalles de tu compra</h2>
                <div class="pago-info" id="pago-info"></div>
                <label for="correo">Correo para entrega:</label>
                <input type="text" name="correo" id="correo" required value="micorreoprueba@gmail.com">
                <a class="whatsapp-link" id="whatsapp-evidencia" href="#" target="_blank">
                    <span class="wa-badge"><i class="fa-brands fa-whatsapp"></i></span>
                    <span class="wa-text">Enviar evidencia por WhatsApp</span>
                </a>
                <button type="submit" class="stepper-btn" style="background:#fbbf24;color:#1e293b;">Finalizar compra</button>
            </div>
        </form>
    </div>

    <!-- Confirmación -->
    <?php if(isset($_GET['confirmacion'])): ?>
    <div class="modal-bg" style="display:flex;">
        <div class="modal" style="text-align:center;">
            <h2 style="color:#38bdf8;"><i class="fa-solid fa-check-circle"></i> ¡Gracias por tu compra!</h2>
            <p>Tu pago está en proceso de verificación.<br>
            Recibirás tus monedas en tu correo una vez confirmado el pago.</p>
            <div style="margin:18px 0;">
                <i class="fa-solid fa-clock fa-spin" style="color:#fbbf24;font-size:2rem;"></i>
                <br>
                <b>Estado:</b> Esperando confirmación de pago
            </div>
            <a class="factura-link" href="?descargar_factura=1"><i class="fa-solid fa-ticket"></i> Descargar boleto de compra</a>
            <br>
            <button class="close-btn" onclick="window.location='tienda2.php'">&times;</button>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Promos Slider JS
        let promoCurrent = 0;
        const promoSlides = document.getElementById('promos-slides');
        const promoTotal = promoSlides.children.length;
        function showPromoSlide(index) {
            promoCurrent = (index + promoTotal) % promoTotal;
            promoSlides.style.transform = `translateX(-${promoCurrent * 100}%)`;
        }
        function movePromoSlide(dir) { showPromoSlide(promoCurrent + dir); }
        setInterval(() => movePromoSlide(1), 7000);

        // Slider JS igual que antes
        let currentSlide = 0;
        const slides = document.getElementById('slides');
        if(slides) {
            const totalSlides = slides.children.length;
            function showSlide(index) {
                currentSlide = (index + totalSlides) % totalSlides;
                slides.style.transform = `translateX(-${currentSlide * 100}%)`;
            }
            function moveSlide(dir) { showSlide(currentSlide + dir); }
            setInterval(() => moveSlide(1), 5000);
        }

        // Fade-in
        document.addEventListener('DOMContentLoaded', () => {
            const fadeEls = document.querySelectorAll('.fade-in');
            fadeEls.forEach((el, i) => {
                setTimeout(() => { el.style.opacity = 1; el.style.transform = 'none'; }, 300 + i*200);
            });
        });

        // Modal stepper
        function abrirModal(id) {
            document.getElementById('modal-bg').style.display = 'flex';
            document.getElementById('producto_id').value = id;
            document.getElementById('form-compra').reset();
            document.getElementById('pago').value = '';
            document.querySelectorAll('.pay-icon-btn').forEach(btn => btn.classList.remove('selected'));
            document.getElementById('btn-continuar').disabled = true;
            document.getElementById('step1').classList.add('active');
            document.getElementById('step2').classList.remove('active');
        }
        function cerrarModal() {
            document.getElementById('modal-bg').style.display = 'none';
        }
        function selectPay(el) {
            document.querySelectorAll('.pay-icon-btn').forEach(btn => btn.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('pago').value = el.getAttribute('data-pay');
            document.getElementById('btn-continuar').disabled = false;
        }
        function continuarPaso2() {
            document.getElementById('step1').classList.remove('active');
            document.getElementById('step2').classList.add('active');
            mostrarInfoPago();
        }
        function mostrarInfoPago() {
            const pago = document.getElementById('pago').value;
            const info = document.getElementById('pago-info');
            let productoId = document.getElementById('producto_id').value;
            let productos = <?=json_encode(getProducts())?>;
            let producto = productos.find(p => p.id == productoId);
            if (!producto) producto = {nombre:'',precio_soles:0,precio_usd:0};
            let html = '';
            if (pago === 'Yape') {
                html = "<b>Yape Perú:</b> 916477262<br>Envía el pago a ese número.<br>Luego sube la captura de pago por WhatsApp.";
            } else if (pago === 'Plin') {
                html = "<b>Plin Perú:</b> 916477262<br>Envía el pago a ese número.<br>Luego sube la captura de pago por WhatsApp.";
            } else if (pago === 'Paypal') {
                html = "<b>Paypal:</b> christhoperr246@gmail.com<br>Envia el pago como 'Amigos y Familia'.<br>Luego sube la captura de pago por WhatsApp.";
            } else if (pago === 'Banco') {
                html = "<b>Banco:</b> BCP<br>CCI: 002-123-456789012345-67<br>Deposita y sube la captura de pago por WhatsApp.";
            }
            info.innerHTML = html;
            // Actualiza link de WhatsApp con datos
            let correo = document.getElementById('correo').value;
            let mensaje = `Hola 👋, subo mi comprobante de pago para SkyBlock.\n\n🛒 Producto: ${producto.nombre}\n💵 Precio: S/${producto.precio_soles} (USD ${producto.precio_usd})\n💳 Método: ${pago}\n📧 Correo: ${correo}\n\nAdjunto mi evidencia.`;
            document.getElementById('whatsapp-evidencia').href = "https://wa.me/51916477262?text=" + encodeURIComponent(mensaje);
        }
        // Actualiza WhatsApp link al cambiar correo
        document.addEventListener('input', function(e){
            if(e.target.id === 'correo') mostrarInfoPago();
        });

        // Free coins slider
        let freeCoinsCurrent = 0;
        const freeCoinsSlides = document.getElementById('free-coins-slides');
        const freeCoinsTotal = freeCoinsSlides.children.length;
        function showFreeCoinsSlide(index) {
            freeCoinsCurrent = (index + freeCoinsTotal) % freeCoinsTotal;
            freeCoinsSlides.style.transform = `translateX(-${freeCoinsCurrent * 100}%)`;
        }
        function moveFreeCoinsSlide(dir) { showFreeCoinsSlide(freeCoinsCurrent + dir); }
        setInterval(() => moveFreeCoinsSlide(1), 7000);
    </script>
</body>
</html>
