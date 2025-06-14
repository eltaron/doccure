import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.preprocessing import LabelEncoder, StandardScaler
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
import joblib

# Load data
df = pd.read_csv("./content/health_sensor_dataset_4000.csv")

# Encode class labels
le = LabelEncoder()
df['Class_encoded'] = le.fit_transform(df['Class'])

# Scale features
scaler = StandardScaler()
features = ['Temperature', 'HeartRate', 'SpO2']
df[features] = scaler.fit_transform(df[features])

# Train/test split
X = df[features]
y = df['Class_encoded']
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train Random Forest
rf = RandomForestClassifier(n_estimators=100, random_state=42)
rf.fit(X_train, y_train)

# ======== الجزء الأهم: الحفظ ========
# حفظ النموذج، الـ Scaler، والـ LabelEncoder
joblib.dump(rf, 'rf.pkl')
joblib.dump(scaler, 'scaler.pkl')
joblib.dump(le, 'le.pkl')

print("✅ Files 'rf.pkl', 'scaler.pkl', and 'le.pkl' have been successfully created!")
