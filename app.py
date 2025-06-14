import joblib
import pandas as pd
from flask import Flask, request, jsonify

# Initialize Flask app
app = Flask(__name__)

# Load the model and preprocessing objects once at startup
try:
    model = joblib.load('rf.pkl')
    scaler = joblib.load('scaler.pkl')
    le = joblib.load('le.pkl')
    print("Model and preprocessing objects loaded successfully")
except FileNotFoundError:
    print("Error: Make sure 'rf.pkl', 'scaler.pkl', and 'le.pkl' are in the same directory.")
    model = scaler = le = None
except Exception as e:
    print(f"Error loading models: {e}")
    model = scaler = le = None

# Define feature names expected by the model
FEATURES = ['Temperature', 'HeartRate', 'SpO2']

# Define API route for prediction
@app.route('/predict', methods=['GET', 'POST'])
def predict():
    if not model or not scaler or not le:
        return jsonify({'error': 'Model or preprocessing objects not loaded'}), 500

    if request.method == 'GET':
        return jsonify({'error': 'Use POST method with JSON data containing Temperature, HeartRate, and SpO2'}), 400

    # 1. Get JSON data from the request
    json_data = request.get_json()
    if not json_data:
        return jsonify({'error': 'No data provided'}), 400

    # 2. Convert data to Pandas DataFrame
    try:
        data_df = pd.DataFrame([json_data], columns=FEATURES)
    except Exception as e:
        return jsonify({'error': f'Invalid data format: {e}'}), 400

    # 3. Validate required fields
    if not all(field in json_data for field in FEATURES):
        return jsonify({'error': f'Missing fields. Required: {FEATURES}'}), 400

    # 4. Apply scaler to the new data
    try:
        data_scaled = scaler.transform(data_df)
    except Exception as e:
        return jsonify({'error': f'Error scaling data: {e}'}), 400

    # 5. Make prediction
    try:
        prediction_encoded = model.predict(data_scaled)
    except Exception as e:
        return jsonify({'error': f'Error making prediction: {e}'}), 400

    # 6. Decode the prediction to text ("Normal" / "Abnormal")
    try:
        prediction_text = le.inverse_transform(prediction_encoded)[0]
    except Exception as e:
        return jsonify({'error': f'Error decoding prediction: {e}'}), 400

    # 7. Return the result as JSON
    return jsonify({'prediction': prediction_text})

# Run the server
if __name__ == '__main__':
    # Listen on all interfaces (important for Docker or network use)
    app.run(host='0.0.0.0', port=5000, debug=True)