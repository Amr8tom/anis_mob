@extends('workspace.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        /* Tab Navigation - Upwork Style */
        .tabs-header {
            display: flex;
            border-bottom: 1px solid var(--upwork-border);
            margin-bottom: 30px;
            overflow-x: auto;
            white-space: nowrap;
            gap: 20px;
        }

        .tab-btn {
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            font-family: var(--font-family);
            font-size: 16px;
            font-weight: 700;
            color: var(--upwork-muted);
            padding: 12px 6px;
            cursor: pointer;
            transition: var(--transition);
        }

        .tab-btn:hover {
            color: var(--upwork-green);
        }

        .tab-btn.active {
            color: var(--upwork-green);
            border-bottom-color: var(--upwork-green);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 24px;
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

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        /* Map styling */
        #map {
            height: 380px;
            width: 100%;
            border-radius: var(--radius-sm);
            border: 1px solid var(--upwork-border);
            margin-bottom: 10px;
            z-index: 10;
        }

        /* Amenities layout */
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 10px;
        }

        .checkbox-card {
            border: 1px solid var(--upwork-border);
            border-radius: var(--radius-sm);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: var(--transition);
            background-color: #ffffff;
        }

        .checkbox-card:hover {
            border-color: var(--upwork-green-dark);
            background-color: var(--upwork-green-soft);
        }

        .checkbox-card input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--upwork-green);
            cursor: pointer;
        }

        .checkbox-card span {
            font-weight: 600;
            font-size: 15px;
        }

        /* Images tab styling */
        .cover-preview {
            width: 100%;
            max-height: 240px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--upwork-border);
            margin-top: 10px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            margin-top: 15px;
        }

        .gallery-item {
            position: relative;
            border-radius: var(--radius-sm);
            overflow: hidden;
            border: 1px solid var(--upwork-border);
            aspect-ratio: 4/3;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-delete-img {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: rgba(223, 32, 32, 0.85);
            color: #ffffff;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: grid;
            place-items: center;
            transition: var(--transition);
        }

        .btn-delete-img:hover {
            background-color: rgba(223, 32, 32, 1);
            transform: scale(1.1);
        }

        /* QR Code section */
        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 10px;
            text-align: center;
        }

        .qr-container {
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid var(--upwork-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .qr-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-family);
            font-size: 15px;
            font-weight: 700;
            padding: 12px 24px;
            border: 1px solid var(--upwork-green);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-action-primary {
            background-color: var(--upwork-green);
            color: #ffffff;
        }

        .btn-action-primary:hover {
            background-color: var(--upwork-green-dark);
            border-color: var(--upwork-green-dark);
        }

        .btn-action-secondary {
            background-color: #ffffff;
            color: var(--upwork-green-dark);
        }

        .btn-action-secondary:hover {
            background-color: var(--upwork-green-soft);
        }

        /* Save bar styling */
        .submit-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--upwork-border);
            display: flex;
            justify-content: flex-end;
        }

        .btn-save {
            background-color: var(--upwork-green);
            color: #ffffff;
            font-family: var(--font-family);
            font-size: 16px;
            font-weight: 700;
            padding: 14px 36px;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-save:hover {
            background-color: var(--upwork-green-dark);
        }

        /* Printing Stylesheet for QR Poster */
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background-color: #ffffff;
            }
            .no-print {
                display: none !important;
            }
        }

        /* Print preview container on screen (hidden by default) */
        #print-area {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="settings-container">
        <h1 class="page-title">إعدادات مساحة العمل</h1>
        <p class="page-subtitle">قم بتحديث تفاصيل مساحة العمل الخاصة بك ومعرض الصور والرمز المخصص لعمليات الدخول.</p>

        <!-- Tabs Navigation -->
        <div class="tabs-header">
            <button type="button" class="tab-btn active" onclick="switchTab(event, 'details-tab')">البيانات الأساسية</button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'location-tab')">الموقع الجغرافي</button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'gallery-tab')">معرض الصور</button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'amenities-tab')">المميزات</button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'drinks-tab')">قائمة المشروبات</button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'occupancy-tab')">الحالة المباشرة</button>
            <button type="button" class="tab-btn" onclick="switchTab(event, 'qr-tab')">رمز الاستجابة السريعة (QR)</button>
        </div>

        <form action="{{ route('workspace.settings.update') }}" method="POST" enctype="multipart/form-data" id="settings-form">
            @csrf
            @method('PUT')

            <!-- Tab 1: Basic Details -->
            <div id="details-tab" class="tab-content active">
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-pen-to-square" style="color: var(--upwork-green);"></i>
                        <span>البيانات الأساسية للمساحة</span>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="name">اسم مساحة العمل</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $workspace->name) }}" required>
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="admin_phone">رقم الواتساب / مسؤول المساحة</label>
                            <input type="tel" id="admin_phone" name="admin_phone" class="form-control" value="{{ old('admin_phone', $workspace->admin_phone) }}" required>
                            @error('admin_phone')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">وصف المساحة</label>
                        <textarea id="description" name="description" class="form-control" placeholder="اكتب وصفاً جذاباً لمساحة العمل الخاصة بك... (الخدمات، الجو العام، شروط الاستخدام)">{{ old('description', $workspace->description) }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="capacity">السعة الإجمالية (عدد الأفراد)</label>
                            <input type="number" id="capacity" name="capacity" class="form-control" min="0" value="{{ old('capacity', $workspace->capacity) }}">
                            @error('capacity')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="day_calculation_hours">ساعات احتساب اليوم</label>
                            <input type="number" id="day_calculation_hours" name="day_calculation_hours" class="form-control" min="1" max="24" value="{{ old('day_calculation_hours', $workspace->day_calculation_hours ?? 8) }}" required>
                            @error('day_calculation_hours')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="open_time">وقت فتح المساحة</label>
                            <input type="text" id="open_time" name="open_time" class="form-control" placeholder="08:00" value="{{ old('open_time', $workspace->open_time) }}">
                            @error('open_time')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="close_time">وقت إغلاق المساحة</label>
                            <input type="text" id="close_time" name="close_time" class="form-control" placeholder="23:00" value="{{ old('close_time', $workspace->close_time) }}">
                            @error('close_time')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Location Map -->
            <div id="location-tab" class="tab-content">
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-map-location-dot" style="color: var(--upwork-green);"></i>
                        <span>الموقع الجغرافي والعنوان</span>
                    </div>

                    <div class="form-group">
                        <label for="address">العنوان بالكامل</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $workspace->address) }}" required>
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>حدد موقع المساحة بدقة على الخريطة</label>
                        <div id="map"></div>
                        
                        <div class="grid-2" style="margin-top: 15px;">
                            <div>
                                <label style="font-size: 12px; color: var(--upwork-muted);">خط العرض (Latitude)</label>
                                <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', $workspace->latitude) }}" readonly required>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: var(--upwork-muted);">خط الطول (Longitude)</label>
                                <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', $workspace->longitude) }}" readonly required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Images & Gallery -->
            <div id="gallery-tab" class="tab-content">
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-images" style="color: var(--upwork-green);"></i>
                        <span>صور المعرض والغلاف</span>
                    </div>

                    <!-- Cover Image Section -->
                    <div class="form-group" style="padding-bottom: 24px; border-bottom: 1px solid var(--upwork-border);">
                        <label for="cover_image">صورة الغلاف الأساسية</label>
                        <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                        @if($workspace->cover_image_url)
                            <div>
                                <label style="font-size: 12px; color: var(--upwork-muted); margin-top: 12px; display: block;">الصورة الحالية:</label>
                                <img src="{{ $workspace->cover_image_url }}" alt="Cover Image" class="cover-preview">
                            </div>
                        @endif
                        @error('cover_image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gallery Section -->
                    <div class="form-group" style="margin-top: 24px;">
                        <label for="gallery_images">أضف صور جديدة للمعرض</label>
                        <input type="file" id="gallery_images" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                        <small style="color: var(--upwork-muted); font-size: 12px; display: block; margin-top: 4px;">يمكنك اختيار صور متعددة لرفعها دفعة واحدة.</small>
                        @error('gallery_images')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('gallery_images.*')
                            <div class="form-error">{{ $message }}</div>
                        @enderror

                        <label style="font-weight: 700; margin-top: 24px; display: block; font-size: 14px;">الصور الحالية بالمعرض (اضغط لحذف أي صورة):</label>
                        <div class="gallery-grid" id="gallery-container">
                            @if($workspace->gallery_images && count($workspace->gallery_images) > 0)
                                @foreach($workspace->gallery_images as $image)
                                    <div class="gallery-item" data-url="{{ $image }}">
                                        <input type="hidden" name="retained_gallery_images[]" value="{{ $image }}">
                                        <img src="{{ $image }}" alt="Gallery Image">
                                        <button type="button" class="btn-delete-img" onclick="deleteGalleryImage(this)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <p style="color: var(--upwork-muted); font-size: 14px; grid-column: 1/-1; text-align: center; padding: 20px;">
                                    لا يوجد صور في المعرض حالياً.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Amenities -->
            <div id="amenities-tab" class="tab-content">
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-mug-hot" style="color: var(--upwork-green);"></i>
                        <span>المميزات والخدمات المتوفرة</span>
                    </div>

                    <p style="color: var(--upwork-muted); font-size: 14px; margin-bottom: 20px;">
                        اختر الخدمات والمميزات المتوفرة في مساحتك لتسهيل عثور المستخدمين عليها في محركات البحث.
                    </p>

                    @php
                        $workspaceAmenities = $workspace->amenities ?? [];
                        $availableAmenities = [
                            'wifi' => ['label' => 'إنترنت سريع (WiFi)', 'icon' => 'fa-wifi'],
                            'ac' => ['label' => 'تكييف هواء', 'icon' => 'fa-wind'],
                            'coffee' => ['label' => 'مشروبات وقهوة', 'icon' => 'fa-coffee'],
                            'printing' => ['label' => 'خدمات الطباعة والنسخ', 'icon' => 'fa-print'],
                            'quiet' => ['label' => 'غرف هادئة / للمذاكرة', 'icon' => 'fa-volume-mute']
                        ];
                    @endphp

                    <div class="amenities-grid">
                        @foreach($availableAmenities as $key => $info)
                            <label class="checkbox-card">
                                <input type="checkbox" name="amenities[]" value="{{ $key }}" {{ in_array($key, $workspaceAmenities) ? 'checked' : '' }}>
                                <i class="fa-solid {{ $info['icon'] }}" style="color: var(--upwork-green-dark); font-size: 18px;"></i>
                                <span>{{ $info['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab 5: Drinks Menu -->
            <div id="drinks-tab" class="tab-content">
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-mug-hot" style="color: var(--upwork-green);"></i>
                        <span>قائمة المشروبات</span>
                    </div>

                    <p style="color: var(--upwork-muted); font-size: 14px; margin-bottom: 20px;">
                        أضف المشروبات المتاحة في مساحتك مع تحديد السعر.
                    </p>

                    <div id="drinks-container">
                        @foreach($workspace->drinks as $index => $drink)
                            <div class="grid-2 drink-row" style="margin-bottom: 15px; align-items: end;">
                                <input type="hidden" name="drinks[{{ $index }}][id]" value="{{ $drink->id }}">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>اسم المشروب</label>
                                    <div style="display: flex; gap: 10px;">
                                        <input type="text" name="drinks[{{ $index }}][icon]" class="form-control" style="width: 60px; text-align: center;" value="{{ $drink->icon }}" required placeholder="☕">
                                        <input type="text" name="drinks[{{ $index }}][name]" class="form-control" style="flex: 1;" value="{{ $drink->name }}" required placeholder="مثال: قهوة تركي">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 0; display: flex; gap: 10px; align-items: flex-end;">
                                    <div style="flex: 1;">
                                        <label>السعر (بالقروش)</label>
                                        <input type="number" name="drinks[{{ $index }}][price_cents]" class="form-control" value="{{ $drink->price_cents }}" required min="0">
                                    </div>
                                    <button type="button" class="btn-remove-drink" onclick="this.closest('.drink-row').remove();" style="background: none; border: none; color: var(--upwork-error); cursor: pointer; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border); height: 45px;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" class="btn-submit" id="add-drink-btn" style="background-color: var(--upwork-blue); padding: 8px 16px; font-size: 14px; width: auto; margin-bottom: 20px; margin-top: 10px;">
                        <i class="fa-solid fa-plus"></i> إضافة مشروب
                    </button>
                </div>
            </div>

            <!-- Tab 6: Live Occupancy & Status -->
            <div id="occupancy-tab" class="tab-content">
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-users" style="color: var(--upwork-green);"></i>
                        <span>الحالة المباشرة والإشغال</span>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="manual_occupancy">تعديل عدد العملاء الحاليين يدوياً</label>
                            <input type="number" id="manual_occupancy" name="manual_occupancy" class="form-control" min="0" value="{{ old('manual_occupancy', $workspace->manual_occupancy) }}" placeholder="اتركه فارغاً للاعتماد على التطبيق">
                            <small style="color: var(--upwork-muted); font-size: 12px; display: block; margin-top: 4px;">إذا تم إدخال رقم هنا، سيتجاهل النظام العدد المحسوب تلقائياً من التطبيق.</small>
                            @error('manual_occupancy')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">حالة المساحة الآن</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="OPEN" {{ old('status', $workspace->status->value) == 'OPEN' ? 'selected' : '' }}>مفتوح (OPEN)</option>
                                <option value="BUSY" {{ old('status', $workspace->status->value) == 'BUSY' ? 'selected' : '' }}>مزدحم (BUSY)</option>
                                <option value="FULL" {{ old('status', $workspace->status->value) == 'FULL' ? 'selected' : '' }}>ممتلئ (FULL)</option>
                                <option value="CLOSED" {{ old('status', $workspace->status->value) == 'CLOSED' ? 'selected' : '' }}>مغلق (CLOSED)</option>
                            </select>
                            @error('status')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 7: QR Code Display -->
            <div id="qr-tab" class="tab-content">
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-qrcode" style="color: var(--upwork-green);"></i>
                        <span>رمز الاستجابة السريعة (QR Code) للمساحة</span>
                    </div>

                    <div class="qr-section">
                        <p style="color: var(--upwork-muted); font-size: 15px; max-width: 580px; margin-bottom: 24px;">
                            هذا الرمز مخصص للمساحة. يجب على الطلاب والمستخدمين مسحه عند الحضور والانصراف لحساب الوقت بشكل دقيق من خلال تطبيق "أنيس".
                        </p>

                        <!-- Element where the QR Code will render -->
                        <div class="qr-container">
                            <div id="qrcode"></div>
                        </div>

                        <div class="qr-actions no-print">
                            <button type="button" class="btn-action btn-action-primary" onclick="downloadQrPdf()">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>تحميل كملف PDF</span>
                            </button>
                            <button type="button" class="btn-action btn-action-secondary" onclick="printQrPoster()">
                                <i class="fa-solid fa-print"></i>
                                <span>طباعة الملصق</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Changes Section (Global) -->
            <div class="submit-section no-print">
                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>حفظ جميع التغييرات</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Hidden Poster for Printing -->
    <div id="print-area">
        <div style="border: 10px solid #14a800; border-radius: 20px; padding: 40px; text-align: center; max-width: 600px; width: 100%;">
            <div style="background-color: #14a800; color: #ffffff; padding: 12px 30px; font-weight: 900; font-size: 26px; border-radius: 99px; display: inline-block; margin-bottom: 30px;">
                تطبيق أنيس
            </div>
            
            <h1 style="font-size: 34px; font-weight: 800; margin-bottom: 10px; color: #001e00;">سجل دخولك هنا</h1>
            <p style="font-size: 18px; color: #5e6d55; margin-bottom: 40px; font-weight: 500;">
                افتح تطبيق أنيس، وامسح الرمز لبدء جلستك في مساحة:
                <br>
                <strong style="font-size: 26px; color: #14a800; display: block; margin-top: 10px;">{{ $workspace->name }}</strong>
            </p>

            <div style="display: flex; justify-content: center; margin-bottom: 40px;">
                <div id="print-qrcode" style="border: 2px solid #e4ece4; padding: 15px; border-radius: 12px;"></div>
            </div>

            <p style="font-size: 14px; color: #5e6d55;">
                ذاكر مع ناس، مش لوحدك.
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <!-- QRCode client side renderer -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- jsPDF client side renderer -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        // Tab switching logic
        function switchTab(evt, tabId) {
            // Hide all tab content
            var tabContents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }

            // Remove active class from buttons
            var tabBtns = document.getElementsByClassName("tab-btn");
            for (var i = 0; i < tabBtns.length; i++) {
                tabBtns[i].classList.remove("active");
            }

            // Show current tab content & mark button active
            document.getElementById(tabId).classList.add("active");
            evt.currentTarget.classList.add("active");

            // If switching to location, force Leaflet to recalculate sizes
            if (tabId === 'location-tab') {
                setTimeout(function() {
                    window.mapInstance.invalidateSize();
                }, 100);
            }
        }

        // Drinks Dynamic Section
        let drinkIndex = {{ count($workspace->drinks) }};
        document.addEventListener('DOMContentLoaded', function() {
            const drinksContainer = document.getElementById('drinks-container');
            const addDrinkBtn = document.getElementById('add-drink-btn');

            if (addDrinkBtn) {
                addDrinkBtn.addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'grid-2 drink-row';
                    row.style.marginBottom = '15px';
                    row.style.alignItems = 'end';
                    row.innerHTML = `
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>اسم المشروب</label>
                            <div style="display: flex; gap: 10px;">
                                <input type="text" name="drinks[${drinkIndex}][icon]" class="form-control" style="width: 60px; text-align: center;" value="☕" required placeholder="☕">
                                <input type="text" name="drinks[${drinkIndex}][name]" class="form-control" style="flex: 1;" value="" required placeholder="مثال: قهوة تركي">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0; display: flex; gap: 10px; align-items: flex-end;">
                            <div style="flex: 1;">
                                <label>السعر (بالقروش - مثال: 15 جنيه = 1500)</label>
                                <input type="number" name="drinks[${drinkIndex}][price_cents]" class="form-control" value="" required min="0">
                            </div>
                            <button type="button" class="btn-remove-drink" onclick="this.closest('.drink-row').remove();" style="background: none; border: none; color: var(--upwork-error); cursor: pointer; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border); height: 45px;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `;
                    drinksContainer.appendChild(row);
                    drinkIndex++;
                });
            }
        });

        // Delete gallery image handler
        function deleteGalleryImage(btn) {
            var item = btn.closest(".gallery-item");
            if (item) {
                item.remove();
            }
            
            // Check if container is empty
            var container = document.getElementById("gallery-container");
            if (container.children.length === 0) {
                container.innerHTML = '<p style="color: var(--upwork-muted); font-size: 14px; grid-column: 1/-1; text-align: center; padding: 20px;">لا يوجد صور في المعرض حالياً.</p>';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. Map Initialization ---
            var initialLat = parseFloat(document.getElementById('latitude').value) || 30.0444;
            var initialLng = parseFloat(document.getElementById('longitude').value) || 31.2357;

            var map = L.map('map').setView([initialLat, initialLng], 15);
            window.mapInstance = map;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            function updateCoordinates(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            }

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });

            // --- 2. QR Codes Initialization ---
            var qrToken = "{{ $workspace->qr_token }}";
            
            // Render on settings page QR Tab
            var qrCodeElement = document.getElementById("qrcode");
            var qrcode = new QRCode(qrCodeElement, {
                text: qrToken,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            // Render on print layout
            var printQrElement = document.getElementById("print-qrcode");
            var printQrcode = new QRCode(printQrElement, {
                text: qrToken,
                width: 250,
                height: 250,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });

        // --- 3. QR Download & Printing Actions ---
        function printQrPoster() {
            window.print();
        }

        function downloadQrPdf() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            // Get the base64 source of the QR code canvas
            var canvas = document.querySelector("#qrcode canvas");
            if (!canvas) {
                // Fallback to image if canvas not supported
                var img = document.querySelector("#qrcode img");
                if (img) {
                    canvas = img;
                }
            }

            if (canvas) {
                var imgData = canvas.src || canvas.toDataURL("image/png");
                
                // Add header details
                doc.setFillColor(20, 168, 0); // Green
                doc.roundedRect(15, 15, 180, 20, 3, 3, 'F');
                
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(16);
                doc.text("Anis Workspace Partner Program", 105, 27, { align: 'center' });

                // Add Workspace Name & Details
                doc.setTextColor(0, 30, 0);
                doc.setFontSize(22);
                doc.text("{{ $workspace->name }}", 105, 55, { align: 'center' });
                
                doc.setFontSize(12);
                doc.text("Scan this QR code using the Anis Mobile App to check-in/checkout.", 105, 68, { align: 'center' });

                // Draw QR Code Image centered
                doc.addImage(imgData, 'PNG', 55, 80, 100, 100);

                doc.setFontSize(10);
                doc.setTextColor(94, 109, 85);
                doc.text("Powered by Anis - Study with others, not alone.", 105, 260, { align: 'center' });
                doc.text("QR Token: {{ $workspace->qr_token }}", 105, 270, { align: 'center' });

                doc.save("workspace-qr-{{ Str::slug($workspace->name) }}.pdf");
            } else {
                alert("تعذر إنشاء ملف PDF، يرجى المحاولة مرة أخرى.");
            }
        }
    </script>
@endsection
