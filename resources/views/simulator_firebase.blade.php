<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Data Simulator</title>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-bottom: 10px;
        }

        #startBtn {
            background-color: #0f9d58;
        }

        #stopBtn {
            background-color: #db4437;
        }

        #submitBtn {
            background-color: #4285f4;
        }

        button:hover {
            opacity: 0.9;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .status {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }

        .success {
            background-color: #e6f4ea;
            color: #34a853;
        }

        .error {
            background-color: #fce8e6;
            color: #d93025;
        }

        .data-display {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-family: monospace;
        }

        .control-panel {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .control-panel input {
            flex: 1;
        }

        .control-panel button {
            flex: 1;
        }

        .current-values {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f0fe;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Sensor Data Simulator</h1>

        <div class="form-group">
            <label for="interval">Auto-update Interval (seconds):</label>
            <input type="number" id="interval" value="5" min="1" max="60">
        </div>

        <div class="control-panel">
            <button id="startBtn">Start Auto Upload</button>
            <button id="stopBtn" disabled>Stop Auto Upload</button>
        </div>

        <div class="form-group">
            <label for="heartRate">Heart Rate (bpm):</label>
            <input type="number" id="heartRate" value="72" min="0" max="200">
        </div>

        <div class="form-group">
            <label for="motion">Motion:</label>
            <select id="motion">
                <option value="false">False</option>
                <option value="true">True</option>
            </select>
        </div>

        <div class="form-group">
            <label for="spO2">SpO2 (%):</label>
            <input type="number" id="spO2" value="98" min="0" max="100">
        </div>

        <div class="form-group">
            <label for="temperature">Temperature (°C):</label>
            <input type="number" id="temperature" value="37.2" step="0.1" min="35" max="42">
        </div>

        <button id="submitBtn">Upload Current Data</button>

        <div id="status" class="status" style="display: none;"></div>
        <!-- === القسم الجديد لعرض النتيجة === -->
        <div id="predictionResult" class="status"
            style="display: none; margin-top: 10px; background-color: #e8f0fe; color: #1a73e8;"></div>
        <div class="current-values">
            <h3>Current Values:</h3>
            <div id="currentValuesDisplay">No data generated yet</div>
        </div>

        <div class="data-display">
            <h3>Data Structure in Firebase:</h3>
            <pre>sensor_data
├── heartRate: [60-100] (normal range)
├── motion: [true/false]
├── spO2: [95-100] (normal range)
├── temperature: [36.0-37.5] (normal range)
└── timestamp: [unix timestamp]</pre>
        </div>
    </div>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyB9OQnl6XIax7apm98QORP4AbQkdQx0Vbk",
            authDomain: "health-1a740.firebaseapp.com",
            databaseURL: "https://health-1a740-default-rtdb.europe-west1.firebasedatabase.app",
            projectId: "health-1a740",
            storageBucket: "health-1a740.firebasestorage.app",
            messagingSenderId: "1065497005872",
            appId: "1:1065497005872:web:58fa9489e77e23dfb342f5"
        };
        firebase.initializeApp(firebaseConfig);

        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const submitBtn = document.getElementById('submitBtn');
        const intervalInput = document.getElementById('interval');
        const statusDiv = document.getElementById('status');
        const currentValuesDisplay = document.getElementById('currentValuesDisplay');

        let simulationInterval = null;
        let isSimulating = false;

        startBtn.addEventListener('click', startAutoUpload);
        stopBtn.addEventListener('click', stopAutoUpload);
        submitBtn.addEventListener('click', uploadData);

        function generateRandomData() {
            const heartRate = Math.random() > 0.9 ? Math.floor(Math.random() * 50) + 100 : Math.floor(Math.random() * 40) +
                60;
            const motion = Math.random() > 0.1;
            const spO2 = Math.random() > 0.9 ? Math.floor(Math.random() * 6) + 90 : Math.floor(Math.random() * 6) + 95;
            const temperature = Math.random() > 0.9 ? (Math.random() * 2.5) + 36.5 : (Math.random() * 1.5) + 36.0;

            return {
                heartRate,
                motion,
                spO2,
                temperature: parseFloat(temperature.toFixed(1)),
                timestamp: Math.floor(Date.now() / 1000)
            };
        }

        function updateFormWithRandomData() {
            const randomData = generateRandomData();

            document.getElementById('heartRate').value = randomData.heartRate;
            document.getElementById('motion').value = randomData.motion;
            document.getElementById('spO2').value = randomData.spO2;
            document.getElementById('temperature').value = randomData.temperature;

            currentValuesDisplay.innerHTML = `
                <p><strong>Heart Rate:</strong> ${randomData.heartRate} bpm</p>
                <p><strong>Motion:</strong> ${randomData.motion ? 'Detected' : 'None'}</p>
                <p><strong>SpO2:</strong> ${randomData.spO2}%</p>
                <p><strong>Temperature:</strong> ${randomData.temperature}°C</p>
                <p><strong>Last update:</strong> ${new Date().toLocaleTimeString()}</p>
            `;

            return randomData;
        }

        function startAutoUpload() {
            if (isSimulating) return;
            const intervalSeconds = parseInt(intervalInput.value) * 1000;
            if (isNaN(intervalSeconds) || intervalSeconds < 1000) {
                alert('Please enter a valid interval (1-60 seconds)');
                return;
            }

            isSimulating = true;
            startBtn.disabled = true;
            stopBtn.disabled = false;

            const initialData = updateFormWithRandomData();
            uploadGeneratedData(initialData);

            simulationInterval = setInterval(() => {
                const data = updateFormWithRandomData();
                uploadGeneratedData(data);
            }, intervalSeconds);

            statusDiv.style.display = 'block';
            statusDiv.className = 'status success';
            statusDiv.innerHTML = `Auto-upload started (every ${intervalSeconds / 1000} seconds)`;
        }

        function stopAutoUpload() {
            if (!isSimulating) return;

            clearInterval(simulationInterval);
            simulationInterval = null;
            isSimulating = false;
            startBtn.disabled = false;
            stopBtn.disabled = true;

            statusDiv.style.display = 'block';
            statusDiv.className = 'status success';
            statusDiv.innerHTML = 'Auto-upload stopped';
        }

        function uploadData() {
            const heartRate = parseInt(document.getElementById('heartRate').value);
            const motion = document.getElementById('motion').value === 'true';
            const spO2 = parseInt(document.getElementById('spO2').value);
            const temperature = parseFloat(document.getElementById('temperature').value);
            const timestamp = Math.floor(Date.now() / 1000);

            const sensorData = {
                heartRate,
                motion,
                spO2,
                temperature,
                timestamp
            };

            uploadGeneratedData(sensorData);
        }

        function uploadGeneratedData(data) {
            const database = firebase.database();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const predictionDiv = document.getElementById('predictionResult');
            database.ref('sensor_data').set(data)
                .then(() => {
                    if (!isSimulating) {
                        statusDiv.style.display = 'block';
                        statusDiv.className = 'status success';
                        statusDiv.innerHTML = `Data successfully uploaded at ${new Date().toLocaleTimeString()}`;
                    }
                    console.log("Data sent to Firebase:", data);
                    predictionDiv.style.display = 'none';
                    predictionDiv.innerHTML = '';
                    fetch("{{ url('/store-sensor-data') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Response from Laravel:", data);
                            // عرض النتيجة التي تم إرجاعها من Laravel
                            if (laravelResponse.prediction) {
                                predictionDiv.style.display = 'block';
                                let resultText =
                                    `✅ Predicted condition: <strong>${laravelResponse.prediction}</strong>`;
                                if (laravelResponse.prediction === 'Abnormal') {
                                    predictionDiv.style.backgroundColor =
                                        '#fce8e6'; // لون أحمر فاتح للحالة غير الطبيعية
                                    predictionDiv.style.color = '#d93025';
                                    resultText =
                                        `⚠️ Predicted condition: <strong>${laravelResponse.prediction}</strong>`;
                                } else {
                                    predictionDiv.style.backgroundColor =
                                        '#e6f4ea'; // لون أخضر فاتح للحالة الطبيعية
                                    predictionDiv.style.color = '#34a853';
                                }
                                predictionDiv.innerHTML = resultText;
                            }
                        })
                        .catch(error => {
                            console.error("Error sending to Laravel API:", error);
                            predictionDiv.style.display = 'block';
                            predictionDiv.className = 'status error';
                            predictionDiv.innerHTML = 'Error getting prediction from server.';
                        });
                })
                .catch((error) => {
                    statusDiv.style.display = 'block';
                    statusDiv.className = 'status error';
                    statusDiv.innerHTML = 'Error uploading data: ' + error.message;
                    console.error("Error writing to Firebase:", error);
                });
        }
    </script>
</body>

</html>
