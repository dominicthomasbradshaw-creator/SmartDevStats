from flask import Flask, jsonify
from pymongo import MongoClient
from datetime import datetime, timedelta

app = Flask(__name__)

# MongoDB Client Setup
MONGO_URL = "YOUR_MONGO_URL"
MONGO_CLIENT = MongoClient(MONGO_URL)

# Access the database and collections
db = MONGO_CLIENT["user_activity_db"]
user_activity_collection = db["user_activity"]

@app.route('/')
def index():
    return "SmartDevStats Hosted On Vercel!!!"

@app.route('/api/stats')
def get_stats():
    now = datetime.utcnow()

    daily_users = user_activity_collection.count_documents({
        "is_group": False,
        "last_activity": {"$gt": now - timedelta(days=1)}
    })
    weekly_users = user_activity_collection.count_documents({
        "is_group": False,
        "last_activity": {"$gt": now - timedelta(weeks=1)}
    })
    monthly_users = user_activity_collection.count_documents({
        "is_group": False,
        "last_activity": {"$gt": now - timedelta(days=30)}
    })
    yearly_users = user_activity_collection.count_documents({
        "is_group": False,
        "last_activity": {"$gt": now - timedelta(days=365)}
    })

    total_users = user_activity_collection.count_documents({"is_group": False})
    total_groups = user_activity_collection.count_documents({"is_group": True})

    stats = {
        "daily_users": daily_users,
        "weekly_users": weekly_users,
        "monthly_users": monthly_users,
        "yearly_users": yearly_users,
        "total_users": total_users,
        "total_groups": total_groups
    }

    return jsonify(stats)

if __name__ == '__main__':
    app.run(debug=True)
