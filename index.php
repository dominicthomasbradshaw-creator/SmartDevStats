<?php
// Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch data from API using curl
$apiUrl = "https://a360api-95nr-mrlam7gpi-dominicthomasbradshaw-4563s-projects.vercel.app";
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Initialize stats HTML
$statsHtml = "";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data && is_array($data)) {
        // Build stats HTML directly
        $statsHtml = "
            <div class='stat col-6 col-md-4 col-lg-2 text-center mb-4 animate__animated animate__zoomIn'>
                <h3 class='text-muted fs-6'>📈 Daily Users</h3>
                <p class='fs-4 fw-bold text-gradient'>" . htmlspecialchars($data['daily_users']) . "</p>
            </div>
            <div class='stat col-6 col-md-4 col-lg-2 text-center mb-4 animate__animated animate__zoomIn'>
                <h3 class='text-muted fs-6'>📅 Weekly Users</h3>
                <p class='fs-4 fw-bold text-gradient'>" . htmlspecialchars($data['weekly_users']) . "</p>
            </div>
            <div class='stat col-6 col-md-4 col-lg-2 text-center mb-4 animate__animated animate__zoomIn'>
                <h3 class='text-muted fs-6'>📆 Monthly Users</h3>
                <p class='fs-4 fw-bold text-gradient'>" . htmlspecialchars($data['monthly_users']) . "</p>
            </div>
            <div class='stat col-6 col-md-4 col-lg-2 text-center mb-4 animate__animated animate__zoomIn'>
                <h3 class='text-muted fs-6'>📊 Yearly Users</h3>
                <p class='fs-4 fw-bold text-gradient'>" . htmlspecialchars($data['yearly_users']) . "</p>
            </div>
            <div class='stat col-6 col-md-4 col-lg-2 text-center mb-4 animate__animated animate__zoomIn'>
                <h3 class='text-muted fs-6'>👥 Total Groups</h3>
                <p class='fs-4 fw-bold text-gradient'>" . htmlspecialchars($data['total_groups']) . "</p>
            </div>
            <div class='stat col-6 col-md-4 col-lg-2 text-center mb-4 animate__animated animate__zoomIn'>
                <h3 class='text-muted fs-6'>👤 Total Users</h3>
                <p class='fs-4 fw-bold text-gradient'>" . htmlspecialchars($data['total_users']) . "</p>
            </div>
        ";
    } else {
        $statsHtml = "<p class='text-danger'>Error: Unable to decode API data.</p>";
        echo "<script>console.error('Failed to decode JSON from API: Invalid data format.');</script>";
    }
} else {
    $statsHtml = "<p class='text-danger'>Error: API request failed (HTTP Code: $httpCode).</p>";
    echo "<script>console.error('API request failed with HTTP code: $httpCode');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SmartDev's Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMhGUKXlY2QpX4s5F0p5Vd2l7uWq2MI" crossorigin="anonymous">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-VHjM6b3e2I+G6ABx0Qh7e5f08O6D/8xk5gk/1+d+D5N3LDsvP5yWn6pD3p+G6LxT2Twi9rZ8s8Y6tw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #f59e0b;
            --secondary-color: #ec4899;
            --text-color: #1e293b;
            --bg-color: #fff7ed;
            --card-bg: #ffffff;
            --border-color: #fed7aa;
            --gradient: linear-gradient(135deg, #f59e0b, #ec4899);
            --text-muted: #6b7280;
            --success-color: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: all 0.5s ease;
            position: relative;
            overflow-x: hidden;
        }

        /* Enhanced Loading Animation */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1e293b, #000);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeOut 0.5s ease-in-out 2.5s forwards;
        }

        #loading-screen h2 {
            font-size: 2.8rem;
            font-weight: 900;
            color: transparent;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            text-shadow: 0 0 25px rgba(245, 158, 11, 0.9);
            animation: typeWriter 2s steps(20) infinite;
            white-space: nowrap;
            overflow: hidden;
            border-right: 5px solid var(--primary-color);
            letter-spacing: 2px;
        }

        #loading-screen .loader {
            width: 70px;
            height: 70px;
            border: 6px solid transparent;
            border-top: 6px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-top: 2rem;
            position: relative;
        }

        #loading-screen .loader::before,
        #loading-screen .loader::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            animation: orbit 2s linear infinite;
        }

        #loading-screen .loader::before {
            width: 10px;
            height: 10px;
            background: var(--secondary-color);
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
        }

        #loading-screen .loader::after {
            width: 8px;
            height: 8px;
            background: #fff;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            animation-delay: 1s;
        }

        @keyframes typeWriter {
            0% { width: 0; }
            50% { width: 100%; }
            100% { width: 0; }
        }

        @keyframes orbit {
            0% { transform: translateX(-50%) rotate(0deg) translateY(-20px); }
            100% { transform: translateX(-50%) rotate(360deg) translateY(-20px); }
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        /* Background Particles */
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: transparent;
        }

        header {
            background-color: var(--card-bg);
            border-bottom: 2px solid var(--border-color);
            padding: 1.5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        header.scrolled {
            transform: translateY(-10px);
        }

        header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: text-shadow 0.3s ease;
        }

        header h1:hover {
            text-shadow: 0 0 12px rgba(99, 102, 241, 0.5);
        }

        .verified-badge {
            width: 26px;
            height: 26px;
            background-color: #1DA1F2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(29, 161, 242, 0.6);
            transition: transform 0.3s ease;
        }

        .verified-badge:hover {
            transform: rotate(360deg);
        }

        .verified-badge::before {
            content: "✓";
        }

        .tagline {
            font-size: 1rem;
            color: var(--text-muted);
            font-style: italic;
            transition: color 0.3s ease;
        }

        .tagline:hover {
            color: var(--primary-color);
        }

        .container {
            max-width: 1400px;
            padding: 3rem 2rem;
        }

        .centered-title {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 3rem;
            color: var(--text-color);
            transition: text-shadow 0.3s ease;
        }

        .centered-title:hover {
            text-shadow: 0 0 15px var(--primary-color);
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            margin-bottom: 3rem;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--gradient);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-12px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--primary-color);
        }

        .card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            transition: color 0.3s ease;
        }

        .card h2:hover {
            color: var(--primary-color);
        }

        .stats {
            padding: 2rem;
        }

        .stat {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 1rem;
            border-radius: 8px;
        }

        .stat:hover {
            transform: scale(1.15) rotate(3deg);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: rgba(99, 102, 241, 0.05);
        }

        .text-gradient {
            color: var(--text-color);
            font-weight: 600;
            transition: text-shadow 0.3s ease;
        }

        .text-gradient:hover {
            text-shadow: 0 0 10px var(--primary-color);
        }

        .text-muted {
            color: var(--text-muted) !important;
            transition: color 0.3s ease;
        }

        .text-muted:hover {
            color: var(--primary-color);
        }

        .update-time {
            text-align: center;
            font-size: 1.1rem;
            color: var(--text-muted);
            padding-bottom: 1.5rem;
            transition: color 0.3s ease;
        }

        .update-time:hover {
            color: var(--primary-color);
        }

        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .status-header span {
            color: var(--success-color);
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: text-shadow 0.3s ease;
        }

        .status-header span:hover {
            text-shadow: 0 0 8px var(--success-color);
        }

        .status-header span::before {
            content: '';
            display: inline-block;
            width: 1.1rem;
            height: 1.1rem;
            background-image: url('https://img.icons8.com/ios-filled/24/0088cc/telegram-app.png');
            background-size: contain;
            background-repeat: no-repeat;
            margin-right: 0.5rem;
            vertical-align: middle;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        th, td {
            padding: 1.2rem;
            font-size: 1rem;
        }

        th {
            background-color: var(--card-bg);
            color: var(--text-color);
            font-weight: 600;
        }

        tr:not(:last-child) td {
            border-bottom: 1px solid var(--border-color);
        }

        .status-online {
            color: var(--success-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: text-shadow 0.3s ease;
        }

        .status-online:hover {
            text-shadow: 0 0 8px var(--success-color);
        }

        .status-online::before {
            content: '💥';
        }

        .service-name {
            transition: color 0.3s ease;
        }

        .service-name:hover {
            color: var(--primary-color);
        }

        .social-icons {
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .social-icons a {
            margin: 0 18px;
            transition: transform 0.4s ease, opacity 0.4s ease, filter 0.4s ease;
            display: inline-block;
            position: relative;
        }

        .social-icons a:hover {
            transform: scale(1.4) rotate(15deg);
            opacity: 0.9;
            filter: brightness(1.2);
        }

        .social-icons a::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .social-icons a:hover::after {
            opacity: 1;
            visibility: visible;
        }

        .social-icons img {
            width: 36px;
            height: 36px;
            filter: drop-shadow(0 0 6px rgba(0, 0, 0, 0.3));
        }

        .footer {
            text-align: center;
            font-size: 1rem;
            color: var(--text-color);
            padding: 3rem 1rem;
            border-top: 2px solid var(--border-color);
            transition: text-shadow 0.3s ease;
        }

        .footer:hover {
            text-shadow: 0 0 12px var(--primary-color);
        }

        .refreshing-container {
            text-align: center;
            margin: 3rem 0;
        }

        .refreshing {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--gradient);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
            transition: transform 0.3s ease;
            position: relative;
        }

        .refreshing:hover {
            transform: scale(1.05);
        }

        .refreshing .circle {
            width: 1.8rem;
            height: 1.8rem;
            border: 5px solid var(--text-color);
            border-top: 5px solid transparent;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .refreshing span {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
            transition: text-shadow 0.3s ease;
        }

        .refreshing span:hover {
            text-shadow: 0 0 10px #fff;
        }

        .progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: var(--primary-color);
            width: 0;
            transition: width 1s linear;
        }

        .chart-container {
            width: 600px;
            height: 400px;
            margin: 3rem auto;
            padding: 10px;
            border-radius: 16px;
            background-color: var(--card-bg);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .chart-container:hover {
            transform: scale(1.02);
        }


        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes glow {
            0% { box-shadow: 0 0 8px rgba(99, 102, 241, 0.5); }
            50% { box-shadow: 0 0 25px rgba(99, 102, 241, 0.8); }
            100% { box-shadow: 0 0 8px rgba(99, 102, 241, 0.5); }
        }

        .glow {
            animation: glow 2s infinite;
        }

        @media (max-width: 768px) {
            .stat {
                flex: 1 1 50%;
            }

            .centered-title {
                font-size: 2rem;
            }

            header h1 {
                font-size: 1.8rem;
            }

            .social-icons img {
                width: 32px;
                height: 32px;
            }

            .chart-container {
                width: 100%;
                height: 300px;
            }

            #loading-screen h2 {
                font-size: 2rem;
            }

            #back-to-top {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Enhanced Loading Screen -->
    <div id="loading-screen">
        <h2>Loading TheSmartDev's Full Statistics</h2>
        <div class="loader"></div>
    </div>

    <!-- Background Particles -->
    <div id="particles-js"></div>

    <header class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <h1>TheSmartDev</h1>
            <div class="verified-badge glow"></div>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div class="tagline">"Trying To make The World Smarter With SmartDev"</div>
        </div>
    </header>

    <div class="container">
        <h2 class="centered-title animate__animated animate__fadeIn">⚙️ TheSmartDev ❄️</h2>
        <div class="card animate__animated animate__fadeInUp">
            <h2 class="px-4 py-3">📊 SmartBot's User's Database</h2>
            <div id="userStats" class="stats row">
                <?php echo $statsHtml; ?>
            </div>
            <div class="update-time" title="Displays the last time the database was updated">
                💥 Last Database Updated At: <span id="dynamicUpdateTime"></span> 🌐
            </div>
            <div class="chart-container glow">
                <canvas id="statsChart"></canvas>
            </div>
        </div>
        <div class="card animate__animated animate__fadeInUp">
            <div class="status-header">
                <h2>⚙️ Bots And API Statistics</h2>
                <span>All Services Are Online🌟</span>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>⚙️ Service</th>
                        <th>❄️ Server Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="service-name"><img src="https://img.icons8.com/ios-filled/24/0088cc/telegram-app.png" alt="Telegram" style="width: 1rem; height: 1rem; vertical-align: middle; margin-right: 0.2rem;">TheSmartDev API</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                    <tr>
                        <td class="service-name"><img src="https://img.icons8.com/ios-filled/24/0088cc/telegram-app.png" alt="Telegram" style="width: 1rem; height: 1rem; vertical-align: middle; margin-right: 0.2rem;">SmartTools 404 Bot</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                    <tr>
                        <td class="service-name"><img src="https://img.icons8.com/ios-filled/24/0088cc/telegram-app.png" alt="Telegram" style="width: 1rem; height: 1rem; vertical-align: middle; margin-right: 0.2rem;">Smart Tools Bot</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                    <tr>
                        <td class="service-name"><img src="https://img.icons8.com/ios-filled/24/0088cc/telegram-app.png" alt="Telegram" style="width: 1rem; height: 1rem; vertical-align: middle; margin-right: 0.2rem;">Smart AI</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="refreshing-container">
        <div class="refreshing glow" title="The page will automatically reload">
            <div class="circle"></div>
            <span id="refreshingText">🔄 Refreshing Database In 56 Seconds</span>
            <div class="progress-bar" id="refreshProgress"></div>
        </div>
    </div>
    <div class="social-icons">
        <a href="https://t.me/TheSmartDev" target="_blank" class="animate__animated animate__pulse animate__infinite" data-tooltip="Join our Telegram">
            <img src="https://img.icons8.com/ios-filled/24/0088cc/telegram-app.png" alt="Telegram" />
        </a>
        <a href="https://facebook.com/abirxdhackz" target="_blank" class="animate__animated animate__pulse animate__infinite" data-tooltip="Follow on Facebook">
            <img src="https://img.icons8.com/ios-filled/24/1877f2/facebook--v1.png" alt="Facebook" />
        </a>
        <a href="https://youtube.com/@abirxdhackz" target="_blank" class="animate__animated animate__pulse animate__infinite" data-tooltip="Subscribe on YouTube">
            <img src="https://img.icons8.com/ios-filled/24/ff0000/youtube-play.png" alt="YouTube" />
        </a>
        <a href="https://github.com/abirxdhackz" target="_blank" class="animate__animated animate__pulse animate__infinite" data-tooltip="Check out our GitHub">
            <img src="https://img.icons8.com/ios-glyphs/24/000000/github.png" alt="GitHub" />
        </a>
        <a href="https://x.com/abirxdhackz" target="_blank" class="animate__animated animate__pulse animate__infinite" data-tooltip="Follow on X">
            <img src="https://img.icons8.com/ios-filled/24/1da1f2/twitter.png" alt="Twitter (X)" />
        </a>
    </div>
    <div class="footer" title="Visit our website for more details">
        © 2025 ⚙️ TheSmartDev | "One Day My Dream Is To Rule The Tech Verse"
    </div>


    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script>
        // Particles.js Initialization
        particlesJS('particles-js', {
            particles: {
                number: { value: 50, density: { enable: true, value_area: 800 } },
                color: { value: '#f59e0b' },
                shape: { type: 'circle' },
                opacity: { value: 0.3, random: true },
                size: { value: 3, random: true },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#ec4899',
                    opacity: 0.2,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'grab' },
                    onclick: { enable: true, mode: 'push' },
                    resize: true
                },
                modes: {
                    grab: { distance: 200, line_linked: { opacity: 0.5 } },
                    push: { particles_nb: 4 }
                }
            },
            retina_detect: true
        });

        // Header Parallax Effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Back to Top Button
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            backToTop.classList.toggle('show', window.scrollY > 300);
        });

        // Countdown and refresh
        let countdown = 60;
        function updateCountdown() {
            const refreshingText = document.getElementById('refreshingText');
            const progressBar = document.getElementById('refreshProgress');
            if (countdown > 1) {
                refreshingText.textContent = `🔄 Refreshing Database In ${countdown} Seconds`;
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else {
                refreshingText.textContent = `🔄 Refreshing Database In 1 Second`;
                setTimeout(() => location.reload(), 1000);
            }
        }

        // Last updated time
        function setLastUpdatedTime() {
            const lastUpdatedElement = document.getElementById('dynamicUpdateTime');
            const now = new Date();
            const hours = now.getHours() % 12 || 12;
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
            const day = now.toLocaleString('en-US', { weekday: 'long' });
            lastUpdatedElement.textContent = `${hours}:${minutes} ${ampm} ${day}`;
        }

        // Restored Chart.js initialization
        function initializeChart() {
            const ctx = document.getElementById('statsChart').getContext('2d');
            const stats = <?php echo json_encode($data ?: []); ?>;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Daily', 'Weekly', 'Monthly', 'Yearly', 'Total Groups', 'Total Users'],
                    datasets: [{
                        label: 'SmartBot Stats',
                        data: [
                            stats.daily_users || 0,
                            stats.weekly_users || 0,
                            stats.monthly_users || 0,
                            stats.yearly_users || 0,
                            stats.total_groups || 0,
                            stats.total_users || 0
                        ],
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        borderColor: '#f59e0b',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'SmartBot User Statistics'
                        }
                    }
                }
            });
        }

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            updateCountdown();
            setLastUpdatedTime();
            initializeChart();
        });
    </script>
</body>
</html>

