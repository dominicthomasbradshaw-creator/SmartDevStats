# TheSmartDev Bot Stats 🌟

A full-stack application for real-time Telegram bot statistics, featuring a Flask API on Vercel and a PHP frontend on a custom domain. Built with MongoDB for data storage and a responsive, auto-refreshing UI. 🚀

**Demo**: [smartdevstats.unaux.com](https://smartdevstats.unaux.com) 🌐

## ✨ Features
- 📊 **Real-time Stats**: Tracks daily, weekly, monthly, yearly users, total users, and groups.
- ⚙️ **Service Status**: Displays bot and API status in a clean table.
- 📱 **Responsive UI**: Mobile-friendly with Animate.css animations.
- 🔄 **Auto-Refresh**: Updates every 60 seconds.
- 🌍 **Social Links**: Connects to Telegram, Facebook, YouTube, GitHub, Twitter (X).
- 🗄️ **MongoDB Backend**: Secure, scalable data storage.
- ☁️ **Vercel Hosting**: Reliable API deployment.

## 🛠️ Tech Stack
- **Backend**: Flask, MongoDB, Vercel
- **Frontend**: PHP, HTML, CSS, JavaScript, Chart.js, Font Awesome, Animate.css
- **Database**: MongoDB Atlas
- **Deployment**: Vercel (API), Custom Domain (Frontend)

## 📋 Prerequisites
- MongoDB Atlas account 🗄️
- Vercel account ☁️
- PHP-supported hosting (e.g., Unaux, Hostinger) 🌐
- Git, Python 3.8+, pip, code editor 🛠️

## 🚀 Setup Guide

### 1. Clone Repository
```bash
git clone https://github.com/abirxdhack/SmartDevStats.git
cd SmartDevStats
```

### 2. Backend (Flask API) 🐍

#### a. Configure MongoDB Atlas
1. Sign up at [mongodb.com](https://www.mongodb.com/cloud/atlas).
2. Create a cluster, database (`user_activity_db`), and collection (`user_activity`).
3. Update `api/app.py` with your MongoDB connection string:
   ```python
   MONGO_URL = "mongodb+srv://<username>:<password>@cluster0.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0"
   ```

#### b. Install Dependencies
```bash
python -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate
pip install -r requirements.txt
```

#### c. Test Locally
```bash
python api/app.py
```
Visit `http://localhost:5000/api/stats` to confirm JSON output.

#### d. Deploy to Vercel
1. Install Vercel CLI:
   ```bash
   npm install -g vercel
   ```
2. Log in:
   ```bash
   vercel login
   ```
3. Ensure `vercel.json` is configured:
   ```json
   {
     "version": 2,
     "builds": [{ "src": "api/app.py", "use": "@vercel/python" }],
     "routes": [{ "src": "/(.*)", "dest": "api/app.py" }]
   }
   ```
4. Deploy:
   ```bash
   vercel
   ```
5. Update `index.php` with your Vercel API URL:
   ```php
   $apiUrl = "https://your-app.vercel.app/api/stats";
   ```

### 3. Frontend (PHP) 🌐

#### a. Customize `index.php`
1. Confirm `$apiUrl` matches your Vercel API.
2. Edit social media links and footer text as needed.
3. Modify CSS in `<style>` for custom branding.

#### b. Host Frontend
1. Upload `index.php` to your hosting’s root (e.g., `/public_html/`).
2. Set up your domain’s nameservers if using a custom domain.
3. Visit your domain to verify the page loads.

### 4. (Optional) Add Sample Data
Insert test data in MongoDB Atlas:
```javascript
{
  "last_activity": ISODate("2025-04-29T12:00:00Z"),
  "is_group": false
},
{
  "last_activity": ISODate("2025-04-28T10:00:00Z"),
  "is_group": true
}
```

### 5. Verify Deployment
- Visit your domain to check stats and auto-refresh.
- Use browser console to debug errors.
- Ensure `https://your-app.vercel.app/api/stats` returns valid JSON.

## 📁 Project Structure
```
SmartDevStats/
├── api/
│   └── app.py       # Flask API
├── index.php        # PHP frontend
├── vercel.json      # Vercel config
├── requirements.txt # Python dependencies
└── README.md        # Documentation
```

## 🐞 Troubleshooting
- **API Errors**: Check MongoDB connection string and cluster status.
- **Stats Not Loading**: Verify `$apiUrl` in `index.php`.
- **Refresh Issues**: Ensure JavaScript is enabled.
- **CORS**: Add to `api/app.py` if needed:
  ```python
  from flask_cors import CORS
  CORS(app)
  ```
- **Hosting**: Confirm PHP 7.4+ and cURL support.

## 🤝 Contributing
1. Fork the repo.
2. Create a branch (`git checkout -b feature/your-feature`).
3. Commit changes (`git commit -m "Add feature"`).
4. Push (`git push origin feature/your-feature`).
5. Open a pull request.


## 📬 Contact
- Telegram: [t.me/TheSmartDev](https://t.me/TheSmartDev)
- Twitter: [x.com/abirxdhack](https://x.com/abirxdhack)
- GitHub: [github.com/abirxdhack](https://github.com/abirxdhack)

Happy coding! 💥⚙️✨
