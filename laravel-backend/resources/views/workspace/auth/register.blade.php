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
        <h1 class="page-title text-center" style="text-align: center;">{{ __('portal.auth.register.title') }}</h1>
        <p class="page-subtitle text-center" style="text-align: center; margin-bottom: 30px;">
            {{ __('portal.auth.register.subtitle') }}
        </p>

        <div class="card">
            <form action="{{ route('workspace.register') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Owner Account Details -->
                <div class="section-header">
                    <i class="fa-solid fa-user-gear"></i> {{ __('portal.auth.register.section_owner') }}
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="full_name">{{ __('portal.auth.register.full_name') }}</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone_number">{{ __('portal.auth.register.phone') }}</label>
                        <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="01xxxxxxxxx" value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="whatsapp_number">{{ __('portal.auth.register.whatsapp') }}</label>
                        <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control" placeholder="01xxxxxxxxx" value="{{ old('whatsapp_number') }}" required>
                        @error('whatsapp_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('portal.auth.register.password') }}</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Workspace Details -->
                <div class="section-header" style="margin-top: 30px;">
                    <i class="fa-solid fa-store"></i> {{ __('portal.auth.register.section_workspace') }}
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="workspace_name">{{ __('portal.auth.register.workspace_name') }}</label>
                        <input type="text" id="workspace_name" name="workspace_name" class="form-control" value="{{ old('workspace_name') }}" required>
                        @error('workspace_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="day_calculation_hours">{{ __('portal.auth.register.day_hours') }}</label>
                        <input type="number" id="day_calculation_hours" name="day_calculation_hours" class="form-control" min="1" max="24" value="{{ old('day_calculation_hours', 8) }}" required>
                        <small style="color: var(--upwork-muted); font-size: 12px; display: block; margin-top: 4px;">{{ __('portal.auth.register.day_hours_hint') }}</small>
                        @error('day_calculation_hours')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">{{ __('portal.auth.register.description') }}</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="{{ __('portal.auth.register.description_placeholder') }}">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="capacity">{{ __('portal.auth.register.capacity') }}</label>
                        <input type="number" id="capacity" name="capacity" class="form-control" min="1" value="{{ old('capacity') }}">
                        @error('capacity')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid-2" style="gap: 10px;">
                        <div class="form-group">
                            <label for="open_time">{{ __('portal.auth.register.open_time') }}</label>
                            <input type="time" id="open_time" name="open_time" class="form-control" value="{{ old('open_time') }}">
                            @error('open_time')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="close_time">{{ __('portal.auth.register.close_time') }}</label>
                            <input type="time" id="close_time" name="close_time" class="form-control" value="{{ old('close_time') }}">
                            @error('close_time')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="form-group">
                    <label>{{ __('portal.auth.register.amenities_label') }}</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px;">
                        @php $availableAmenities = ['wifi' => __('portal.auth.register.amenity_wifi'), 'ac' => __('portal.auth.register.amenity_ac'), 'coffee' => __('portal.auth.register.amenity_coffee'), 'printing' => __('portal.auth.register.amenity_printing'), 'quiet' => __('portal.auth.register.amenity_quiet')]; @endphp
                        @foreach($availableAmenities as $key => $label)
                            <label style="display: flex; align-items: center; gap: 5px; font-weight: normal; font-size: 14px; cursor: pointer;">
                                <input type="checkbox" name="amenities[]" value="{{ $key }}" {{ in_array($key, old('amenities', [])) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    @error('amenities')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Images -->
                <div class="section-header" style="margin-top: 30px;">
                    <i class="fa-solid fa-images"></i> {{ __('portal.auth.register.section_images') }}
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="cover_image">{{ __('portal.auth.register.cover') }}</label>
                        <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                        @error('cover_image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="gallery_images">{{ __('portal.auth.register.gallery') }}</label>
                        <input type="file" id="gallery_images" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                        @error('gallery_images')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Drinks / Menu -->
                <div class="section-header" style="margin-top: 30px;">
                    <i class="fa-solid fa-mug-hot"></i> {{ __('portal.auth.register.section_drinks') }}
                </div>
                
                <div id="drinks-container">
                    <!-- Dynamic drinks will be appended here -->
                </div>
                <button type="button" class="btn-submit" id="add-drink-btn" style="background-color: var(--upwork-blue); padding: 8px 16px; font-size: 14px; width: auto; margin-bottom: 20px;">
                    <i class="fa-solid fa-plus"></i> {{ __('portal.auth.register.add_drink') }}
                </button>

                <div class="form-group">
                    <label for="address">{{ __('portal.auth.register.address') }}</label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="{{ __('portal.auth.register.address_placeholder') }}" value="{{ old('address') }}" required>
                    @error('address')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Geolocation coordinates (Map) -->
                <div class="form-group">
                    <label>{{ __('portal.auth.register.map_label') }}</label>
                    <p style="color: var(--upwork-muted); font-size: 13px; margin-bottom: 10px;">
                        {{ __('portal.auth.register.map_hint') }}
                    </p>
                    <div id="map"></div>

                    <div class="grid-2" style="margin-top: 10px;">
                        <div>
                            <label style="font-size: 12px; color: var(--upwork-muted);">{{ __('portal.auth.register.latitude') }}</label>
                            <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', '30.0444') }}" readonly required>
                            @error('latitude')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label style="font-size: 12px; color: var(--upwork-muted);">{{ __('portal.auth.register.longitude') }}</label>
                            <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', '31.2357') }}" readonly required>
                            @error('longitude')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" style="margin-top: 20px;">
                    <i class="fa-solid fa-rocket"></i>
                    <span>{{ __('portal.auth.register.submit') }}</span>
                </button>
            </form>
        </div>

        <div class="auth-footer">
            {{ __('portal.auth.register.footer') }} <a href="{{ route('workspace.login') }}">{{ __('portal.auth.register.footer_link') }}</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    @php
        $registerI18n = [
            'drink_name' => __('portal.auth.register.drink_name'),
            'drink_name_placeholder' => __('portal.auth.register.drink_name_placeholder'),
            'drink_price' => __('portal.auth.register.drink_price'),
        ];
    @endphp
    <script>
        const REGISTER_I18N = {!! json_encode($registerI18n, JSON_UNESCAPED_UNICODE) !!};
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

            // Drinks Dynamic Section
            let drinkIndex = 0;
            const drinksContainer = document.getElementById('drinks-container');
            const addDrinkBtn = document.getElementById('add-drink-btn');

            function addDrinkRow(name = '', icon = '☕', priceCents = '') {
                const row = document.createElement('div');
                row.className = 'grid-2';
                row.style.marginBottom = '15px';
                row.style.alignItems = 'end';
                row.innerHTML = `
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>${REGISTER_I18N.drink_name}</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" name="drinks[${drinkIndex}][icon]" class="form-control" style="width: 60px; text-align: center;" value="${icon}" required placeholder="☕">
                            <input type="text" name="drinks[${drinkIndex}][name]" class="form-control" style="flex: 1;" value="${name}" required placeholder="${REGISTER_I18N.drink_name_placeholder}">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0; display: flex; gap: 10px; align-items: flex-end;">
                        <div style="flex: 1;">
                            <label>${REGISTER_I18N.drink_price}</label>
                            <input type="number" name="drinks[${drinkIndex}][price_cents]" class="form-control" value="${priceCents}" required min="0">
                        </div>
                        <button type="button" class="btn-remove-drink" style="background: none; border: none; color: var(--upwork-error); cursor: pointer; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border); height: 45px;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                `;
                
                row.querySelector('.btn-remove-drink').addEventListener('click', function() {
                    row.remove();
                });

                drinksContainer.appendChild(row);
                drinkIndex++;
            }

            addDrinkBtn.addEventListener('click', function() {
                addDrinkRow();
            });

            // Restore old drinks input or initialize with one empty row
            @if(old('drinks'))
                @foreach(old('drinks') as $drink)
                    addDrinkRow(
                        {!! json_encode($drink['name'] ?? '') !!},
                        {!! json_encode($drink['icon'] ?? '☕') !!},
                        {!! json_encode($drink['price_cents'] ?? '') !!}
                    );
                @endforeach
            @else
                addDrinkRow();
            @endif
        });
    </script>
@endsection
