# نظام مراقبة وتشخيص الحالة الصحية باستخدام تعلم الآلة

هذا المشروع هو تطبيق ويب متكامل لمراقبة المؤشرات الحيوية للمرضى وتشخيص حالتهم الصحية (طبيعية أو غير طبيعية) في الوقت الفعلي باستخدام نموذج تعلم الآلة.

## 🌟 الميزات الرئيسية

-   **محاكاة البيانات الحيوية:** واجهة ويب لإرسال بيانات حيوية (درجة الحرارة، معدل نبض القلب، نسبة تشبع الأكسجين) بشكل يدوي أو تلقائي.
-   **تخزين البيانات في الوقت الفعلي:** استخدام Firebase Realtime Database لتخزين البيانات الحيوية فور إرسالها.
-   **تكامل مع قاعدة بيانات علائقية:** تخزين البيانات بشكل دائم في قاعدة بيانات (MySQL/PostgreSQL) عبر إطار عمل Laravel.
-   **تشخيص ذكي:** استدعاء خدمة ذكاء اصطناعي (API) مبنية بلغة Python لتحليل البيانات وتشخيص الحالة الصحية فورًا.
-   **تحديث فوري للواجهة:** عرض نتيجة التشخيص للمستخدم على صفحة الويب مباشرة بعد تحليل البيانات.
-   **بنية قابلة للتطوير:** فصل الواجهة الأمامية (Frontend)، الخلفية (Backend)، وخدمة الذكاء الاصطناعي (AI Service) لسهولة الصيانة والتطوير.

## 🛠️ التقنيات المستخدمة

-   **الواجهة الخلفية (Backend):** Laravel
-   **الواجهة الأمامية (Frontend):** HTML, CSS, JavaScript
-   **قاعدة البيانات:** Firebase Realtime Database, MySQL/PostgreSQL
-   **خدمة الذكاء الاصطناعي (AI Service):** Python, Flask, Scikit-learn

## 📂 بنية المشروع

```
Project_Root/
├── ai/                     # مجلد خدمة الذكاء الاصطناعي (Python/Flask)
│   ├── api.py              # الكود الرئيسي للـ API
│   ├── rf.pkl              # ملف نموذج Random Forest
│   ├── scaler.pkl          # ملف كائن تحجيم البيانات (Scaler)
│   ├── le.pkl              # ملف كائن ترميز التسميات (LabelEncoder)
│   └── requirements.txt    # المكتبات المطلوبة للخدمة
│
├── laravel_project/        # مجلد مشروع Laravel
│   ├── app/
│   ├── public/
│   ├── resources/
│   └── ...
│
└── start_server.bat        # ملف لتشغيل خادم الذكاء الاصطناعي محليًا
```

## 🚀 كيفية التثبيت والتشغيل

لتشغيل المشروع بالكامل على جهازك المحلي، اتبع الخطوات التالية:

### المتطلبات الأساسية

-   [PHP](https://www.php.net/downloads.php) (إصدار متوافق مع Laravel)
-   [Composer](https://getcomposer.org/)
-   [Node.js & npm](https://nodejs.org/)
-   [Python](https://www.python.org/downloads/)
-   حساب على [Firebase](https://firebase.google.com/)

---

### 1. إعداد الواجهة الخلفية (Laravel)

1.  **استنسخ المستودع:**

    ```bash
    git clone https://github.com/YourUsername/YourRepoName.git
    cd YourRepoName/laravel_project
    ```

2.  **ثبّت الاعتماديات:**

    ```bash
    composer install
    npm install
    ```

3.  **إعداد ملف البيئة (`.env`):**

    -   انسخ ملف `.env.example` إلى `.env`:
        ```bash
        cp .env.example .env
        ```
    -   قم بتكوين إعدادات قاعدة البيانات (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4.  **أنشئ مفتاح التطبيق:**

    ```bash
    php artisan key:generate
    ```

5.  **شغّل الـ Migrations لإنشاء جداول قاعدة البيانات:**

    ```bash
    php artisan migrate
    ```

6.  **شغّل خادم Laravel:**
    ```bash
    php artisan serve
    ```
    الآن، يجب أن يعمل تطبيق Laravel على `http://127.0.0.1:8000`.

---

### 2. إعداد خدمة الذكاء الاصطناعي (Python)

لتشغيل خدمة الذكاء الاصطناعي محليًا:

1.  **انتقل إلى مجلد `ai`:**

    ```bash
    cd ../ai
    ```

    (إذا كنت داخل مجلد `laravel_project`)

2.  **شغّل خادم الـ API:**
    ```bash
    python api.py
    ```
    أو ببساطة، يمكنك النقر المزدوج على ملف `start_server.exe` الموجود في المجلد الرئيسي للمشروع.
    سيتم تشغيل الخادم على `http://127.0.0.1:5000`.

### 3. إعداد Firebase

1.  اذهب إلى مشروعك على [Firebase Console](https://console.firebase.google.com/).
2.  انتقل إلى `Realtime Database` > `Rules`.
3.  **لأغراض التطوير**، يمكنك تحديث القواعد للسماح بالقراءة والكتابة للجميع. **(غير آمن للبيئة الإنتاجية!)**
    ```json
    {
        "rules": {
            ".read": "true",
            ".write": "true"
        }
    }
    ```
4.  تأكد من أن بيانات اعتماد Firebase في ملف الواجهة الأمامية (blade view) صحيحة.

## 📈 كيفية الاستخدام

1.  تأكد من أن خادم Laravel وخادم Python يعملان.
2.  افتح المتصفح واذهب إلى صفحة محاكاة البيانات في تطبيق Laravel (عادةً على `http://127.0.0.1:8000/your-page`).
3.  يمكنك إدخال القيم يدويًا والضغط على "Upload Current Data" أو بدء الرفع التلقائي بالضغط على "Start Auto Upload".
4.  راقب تحديث البيانات في Firebase، وظهور نتيجة التشخيص ("Normal" أو "Abnormal") على الصفحة فورًا.

## 🤝 المساهمة

نرحب بالمساهمات! إذا كان لديك اقتراحات لتحسين المشروع، يرجى فتح "issue" أو إرسال "pull request".
