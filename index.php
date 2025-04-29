<?php
// Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch data from API using curl
$apiUrl = "https://stats-rouge.vercel.app/api/stats";
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
            <div class='stat'>
                <h3>📈 Daily Users</h3>
                <p>" . htmlspecialchars($data['daily_users']) . "</p>
            </div>
            <div class='stat'>
                <h3>📅 Weekly Users</h3>
                <p>" . htmlspecialchars($data['weekly_users']) . "</p>
            </div>
            <div class='stat'>
                <h3>📆 Monthly Users</h3>
                <p>" . htmlspecialchars($data['monthly_users']) . "</p>
            </div>
            <div class='stat'>
                <h3>📊 Yearly Users</h3>
                <p>" . htmlspecialchars($data['yearly_users']) . "</p>
            </div>
            <div class='stat'>
                <h3>👥 Total Groups</h3>
                <p>" . htmlspecialchars($data['total_groups']) . "</p>
            </div>
            <div class='stat'>
                <h3>👤 Total Users</h3>
                <p>" . htmlspecialchars($data['total_users']) . "</p>
            </div>
        ";
    } else {
        $statsHtml = "<p>Error: Unable to decode API data.</p>";
        echo "<script>console.error('Failed to decode JSON from API: Invalid data format.');</script>";
    }
} else {
    $statsHtml = "<p>Error: API request failed (HTTP Code: $httpCode).</p>";
    echo "<script>console.error('API request failed with HTTP code: $httpCode');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SmartDev's Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMhGUKXlY2QpX4s5F0p5Vd2l7uWq2MI" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-VHjM6b3e2I+G6ABx0Qh7e5f08O6D/8xk5gk/1+d+D5N3LDsvP5yWn6pD3p+G6LxT2Twi9rZ8s8Y6tw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        scroll-behavior: smooth;
    }

    body {
        background-color: #f3f4f6;
        color: #1f2937;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem;
        background-color: #ffffff;
        color: #1f2937;
        transition: background-color 0.3s ease;
        border-bottom: 2px solid #e5e7eb;
    }

    header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(90deg, #7c3aed, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .verified-badge {
        width: 20px;
        height: 20px;
        background-color: #1DA1F2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        font-weight: bold;
    }

    .verified-badge::before {
        content: "✓";
    }

    header .tagline {
        font-size: 0.85rem;
        color: #374151;
        font-style: italic;
    }

    .container {
        padding: 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .centered-title {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #4f46e5;
    }

    .card {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card h2 {
        font-size: 1.25rem;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .stats {
        display: flex;
        justify-content: space-between;
        padding: 2rem 1rem 1rem 1rem;
        flex-wrap: wrap;
        text-align: center;
    }

    .stat {
        flex: 1 1 25%;
        padding: 1rem 0;
    }

    .stat h3 {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .stat p {
        font-size: 1.5rem;
        color: #4f46e5;
        font-weight: 500;
    }

    .update-time {
        text-align: center;
        font-size: 0.85rem;
        color: #6b7280;
        padding-bottom: 1rem;
    }

    .update-time:hover {
        color: #1f2937;
        text-decoration: underline;
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .status-header h2 {
        margin: 0;
    }

    .status-header span {
        color: #10b981;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .status-header span::before {
        content: '❄️';
        margin-right: 0.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    th, td {
        text-align: left;
        padding: 1rem;
        font-size: 0.95rem;
    }

    th {
        color: #374151;
        background-color: #f9fafb;
    }

    tr:not(:last-child) td {
        border-bottom: 1px solid #e5e7eb;
    }

    .status-online {
        color: #10b981;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-online::before {
        content: '💥';
    }

    .social-icons {
        text-align: center;
        padding-bottom: 1rem;
        margin-top: 2rem;
    }

    .social-icons a {
        margin: 0 10px;
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .social-icons a:hover {
        transform: scale(1.1);
    }

    .social-icons img {
        width: 24px;
        height: 24px;
        vertical-align: middle;
    }

    .footer {
        text-align: center;
        font-size: 0.8rem;
        color: #6b7280;
        padding: 2rem 1rem;
        border-top: 2px solid #e5e7eb;
    }

    .footer:hover {
        color: #1f2937;
    }

    .animate__animated.animate__fadeInUp {
        --animate-duration: 1.5s;
    }

    .animate__animated.animate__fadeIn {
        --animate-duration: 1s;
    }

    .refreshing-container {
        text-align: center;
        margin: 2rem 0;
    }

    .refreshing {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .refreshing .circle {
        width: 1rem;
        height: 1rem;
        border: 2px solid #1f2937;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .refreshing span {
        font-size: 1rem;
        font-weight: 500;
    }

    .refreshing span:hover {
        text-decoration: underline;
    }

    .service-name {
        font-weight: bold;
    }

    .chart-container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
    <script>
        let countdown = 60;
        function updateCountdown() {
            const refreshingText = document.getElementById('refreshingText');
            if (countdown > 1) {
                refreshingText.textContent = `🔄 Refreshing Database In ${countdown} Seconds`;
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else {
                refreshingText.textContent = `🔄 Refreshing Database In 1 Second`;
                location.reload();
            }
        }
        function setLastUpdatedTime() {
            const lastUpdatedElement = document.getElementById('dynamicUpdateTime');
            const now = new Date();
            const hours = now.getHours() % 12 || 12;
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
            const day = now.toLocaleString('en-US', { weekday: 'long' });
            lastUpdatedElement.textContent = `${hours}:${minutes} ${ampm} ${day}`;
        }
        document.addEventListener('DOMContentLoaded', () => {
            updateCountdown();
            setLastUpdatedTime();
        });
    </script>
</head>
<body>
<header>
    <div style="display: flex; align-items: center; gap: 8px;">
        <h1 style="color: #7c3aed; font-weight: 700;">
            TheSmartDev 
        </h1>
        <div class="verified-badge"></div>
    </div>
    <div class="tagline">"Trying To make The World Smarter With SmartDev"</div>
</header>

    <div class="container">
        <div class="centered-title">⚙️ TheSmartDev ❄️</div>
        <div class="card animate__animated animate__fadeInUp">
            <h2>📊 SmartBot's User's Database</h2>
            <div id="userStats" class="stats">
                <?php echo $statsHtml; ?>
            </div>
            <div class="update-time" title="Displays the last time the database was updated">
                💥Last Database Updated At: <span id="dynamicUpdateTime"></span> 🌐
            </div>
        </div>
        <div class="card animate__animated animate__fadeInUp">
            <div class="status-header">
                <h2>⚙️ Bots And API Statistics</h2>
                <span>All Services Are Online🌟</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>⚙️ Service</th>
                        <th>❄️ Server Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="service-name">⚙️TheSmartDev API</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                    <tr>
                        <td class="service-name">❄️SmartTools 404 Bot</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                    <tr>
                        <td class="service-name">❄️Smart Tools Bot</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                    <tr>
                        <td class="service-name">❄️Smart AI</td>
                        <td class="status-online" title="Service is operational">Alive</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="refreshing-container">
        <div class="refreshing" title="The page will automatically reload">
            <div class="circle"></div>
            <span id="refreshingText">🔄 Refreshing Database In 60 Seconds</span>
        </div>
    </div>
    <div class="social-icons">
        <a href="https://t.me/TheSmartDev" target="_blank">
            <img src="https://img.icons8.com/ios-filled/24/0088cc/telegram-app.png" alt="Telegram" />
        </a>
        <a href="https://facebook.com/abirxdhackz" target="_blank">
            <img src="https://img.icons8.com/ios-filled/24/1877f2/facebook--v1.png" alt="Facebook" />
        </a>
        <a href="https://youtube.com/@abirxdhackz" target="_blank">
            <img src="https://img.icons8.com/ios-filled/24/ff0000/youtube-play.png" alt="YouTube" />
        </a>
        <a href="https://github.com/abirxdhackz" target="_blank">
            <img src="https://img.icons8.com/ios-glyphs/24/000000/github.png" alt="GitHub" />
        </a>
        <a href="https://x.com/abirxdhackz" target="_blank">
            <img src="https://img.icons8.com/ios-filled/24/1da1f2/twitter.png" alt="Twitter (X)" />
        </a>
    </div>
    <div class="footer" title="Visit our website for more details">
        © 2025 ⚙️ TheSmartDev | "One Day My Dream Is To Rule The Tech Verse"
    </div>
</body>
</html>