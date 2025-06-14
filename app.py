# api.py

import joblib
import pandas as pd
from flask import Flask, request, jsonify

# تهيئة تطبيق Flask
app = Flask(__name__)

# تحميل النموذج والكائنات المحفوظة عند بدء التشغيل مرة واحدة فقط
try:
    model = joblib.load('rf.pkl')
    scaler = joblib.load('scaler.pkl')
    le = joblib.load('le.pkl')
    print("Model and preprocessing objects loaded successfully")
except FileNotFoundError:
    print("Error: Make sure 'rf.pkl', 'scaler.pkl', and 'le.pkl' are in the same directory.")
    model = scaler = le = None

# تحديد أسماء الميزات التي يتوقعها النموذج
FEATURES = ['Temperature', 'HeartRate', 'SpO2']

# تعريف مسار الـ API للتنبؤ
@app.route('/predict', methods=['POST'])
def predict():
    if not model:
        return jsonify({'error': 'Model not loaded'}), 500

    # 1. الحصول على بيانات JSON من الطلب
    json_data = request.get_json()
    if not json_data:
        return jsonify({'error': 'No data provided'}), 400

    # 2. تحويل البيانات إلى Pandas DataFrame
    # يجب أن نتأكد من أن البيانات بنفس الترتيب الذي تم تدريب النموذج عليه
    try:
        data_df = pd.DataFrame([json_data], columns=FEATURES)
    except Exception as e:
        return jsonify({'error': f'Invalid data format: {e}'}), 400


    # 3. تطبيق الـ Scaler على البيانات الجديدة
    data_scaled = scaler.transform(data_df)

    # 4. إجراء التنبؤ
    prediction_encoded = model.predict(data_scaled)

    # 5. فك ترميز النتيجة للحصول على النص ("Normal" / "Abnormal")
    prediction_text = le.inverse_transform(prediction_encoded)

    # 6. إرجاع النتيجة كـ JSON
    return jsonify({'prediction': prediction_text[0]})

# تشغيل الخادم
if __name__ == '__main__':
    # استمع على جميع الواجهات (مهم عند استخدام Docker أو في الشبكة)
    app.run(host='0.0.0.0', port=5000, debug=True)
