<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StoreDataController extends Controller
{
    public function store(Request $request)
    {
        // يمكنك ربط البيانات بمريض معيّن لاحقاً
        $data = $request->validate([
            'heartRate' => 'required|integer',
            'motion' => 'required|boolean',
            'spO2' => 'required|integer',
            'temperature' => 'required|numeric',
            'timestamp' => 'required|integer',
        ]);
        $data['timestamp'] = date('Y-m-d H:i:s', $data['timestamp']);
        $sensorRecord = SensorData::create($data);
        // 2. تجهيز البيانات لإرسالها إلى API بايثون
        $features = [
            'Temperature' => $data['temperature'],
            'HeartRate' => $data['heartRate'],
            'SpO2' => $data['spO2']
        ];

        $predictionResult = 'N/A'; // قيمة افتراضية

        try {
            // 3. إرسال طلب POST إلى Flask API
            // تأكد من أن عنوان IP والمنفذ صحيحان
            $response = Http::post('http://127.0.0.1:5000/predict', $features);

            if ($response->successful()) {
                // 4. إذا نجح الطلب، احصل على النتيجة وقم بتحديث السجل
                $predictionResult = $response->json()['prediction'];
                $sensorRecord->prediction = $predictionResult;
                $sensorRecord->save();
            } else {
                // سجل الخطأ إذا فشل الاتصال بـ API
                Log::error('Failed to get prediction from Python API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            // سجل الخطأ إذا لم يتمكن من الاتصال بالخادم نهائياً
            Log::error('Could not connect to Python API', ['error' => $e->getMessage()]);
        }

        // 5. إرجاع رسالة النجاح مع النتيجة إلى JavaScript
        return response()->json([
            'message' => 'Data stored and analyzed successfully',
            'prediction' => $predictionResult
        ], 200);
    }
}
