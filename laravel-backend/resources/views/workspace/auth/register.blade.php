@extends('workspace.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        .auth-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-family: var(--font-family);
            font-size: 15px;
            border: 1px solid var(--upwork-input-border);
            border-radius: var(--radius-sm);
            background-color: #ffffff;
            color: var(--upwork-slate);
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--upwork-green);
            box-shadow: 0 0 0 3px rgba(20, 168, 0, 0.1);
        }

        .form-error {
            color: var(--upwork-error);
            font-size: 13px;
            font-weight: 600;
            margin-top: 6px;
        }

        /* Map styling */
        #map {
            height: 350px;
            width: 100%;
            border-radius: var(--radius-sm);
            border: 1px solid var(--upwork-border);
            margin-bottom: 10px;
            z-index: 10;
        }

        .btn-submit {
            background-color: var(--upwork-green);
            color: #ffffff;
            font-family: var(--font-family);
            font-size: 16px;
            font-weight: 700;
            padding: 14px 28px;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background-color: var(--upwork-green-dark);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            color: var(--upwork-muted);
            font-size: 15px;
        }

        .auth-footer a {
            color: var(--upwork-green);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .section-header {
            font-size: 16px;
            font-weight: 800;
            color: var(--upwork-green);
            margin-top: 10px;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--upwork-border);
        }
    </style>
@endsection

@section('content')
    <div class="auth-container">
        <h1 class="page-title text-center" style="text-align: center;">سجل مساحة العمل الخاصة بك</h1>
        <p class="page-subtitle text-center" style="text-align: center; margin-bottom: 30px;">
            انضم إلى شبكة "أنيس" وابدأ باستقبال الطلاب والمستقلين في مساحتك.
        </p>

        <div class="card">
            <form action="{{ route('workspace.register') }}" method="POST">
                @csrf

                <!-- Owner Account Details -->
                <div class="section-header">
                    <i class="fa-solid fa-user-gear"></i> بيانات حساب الشريك (المالك)
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="full_name">الاسم بالكامل</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone_number">رقم الهاتف (لتسجيل الدخول)</label>
                        <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="01xxxxxxxxx" value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="whatsapp_number">رقم الواتساب (للتواصل مع العملاء)</label>
                        <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control" placeholder="01xxxxxxxxx" value="{{ old('whatsapp_number') }}" required>
                        @error('whatsapp_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Workspace Details -->
                <div class="section-header" style="margin-top: 30px;">
                    <i class="fa-solid fa-store"></i> بيانات مساحة العمل
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="workspace_name">اسم مساحة العمل</label>
                        <input type="text" id="workspace_name" name="workspace_name" class="form-control" value="{{ old('workspace_name') }}" required>
                        @error('workspace_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="day_calculation_hours">ساعات احتساب اليوم</label>
                        <input type="number" id="day_calculation_hours" name="day_calculation_hours" class="form-control" min="1" max="24" value="{{ old('day_calculation_hours', 8) }}" required>
                        <small style="color: var(--upwork-muted); font-size: 12px; display: block; margin-top: 4px;">عدد الساعات التي تعادل استهلاك يوم اشتراك كامل للعميل.</small>
                        @error('day_calculation_hours')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">العنوان بالتفصيل</label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="مثال: 12 شارع التسعين، التجمع الخامس، القاهرة" value="{{ old('address') }}" required>
                    @error('address')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Geolocation coordinates (Map) -->
                <div class="form-group">
                    <label>الموقع الجغرافي على الخريطة</label>
                    <p style="color: var(--upwork-muted); font-size: 13px; margin-bottom: 10px;">
                        قم بسحب الدبوس أو اضغط على موقع مساحتك على الخريطة لتحديد الإحداثيات بدقة.
                    </p>
                    <div id="map"></div>
                    
                    <div class="grid-2" style="margin-top: 10px;">
                        <div>
                            <label style="font-size: 12px; color: var(--upwork-muted);">خط العرض (Latitude)</label>
                            <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', '30.0444') }}" readonly required>
                            @error('latitude')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label style="font-size: 12px; color: var(--upwork-muted);">خط الطول (Longitude)</label>
                            <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', '31.2357') }}" readonly required>
                            @error('longitude')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" style="margin-top: 20px;">
                    <i class="fa-solid fa-rocket"></i>
                    <span>تسجيل المساحة والبدء</span>
                </button>
            </form>
        </div>

        <div class="auth-footer">
            لديك حساب مسجل بالفعل؟ <a href="{{ route('workspace.login') }}">تسجيل الدخول من هنا</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial Coordinates (Default Cairo)
            var initialLat = parseFloat(document.getElementById('latitude').value) || 30.0444;
            var initialLng = parseFloat(document.getElementById('longitude').value) || 31.2357;

            // 1. Initialize Map
            var map = L.map('map').setView([initialLat, initialLng], 13);

            // 2. Add OpenStreetMap Tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // 3. Create Draggable Marker
            var marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            // 4. Update Inputs when Marker Drags
            function updateCoordinates(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            }

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

            // 5. Update Marker when Map is Clicked
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });

            // Trigger initial state update
            updateCoordinates(initialLat, initialLng);
        });
    </script>
@endsection
