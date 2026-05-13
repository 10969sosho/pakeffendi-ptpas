@extends('guest.layouts.app')

@section('title', 'Edit Alamat - PAS Market')

@section('content')
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('guest.profile.index') }}">Profil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('guest.profile.addresses.index') }}">Alamat</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <h1 class="h3 fw-bold text-secondary mb-0">Edit Alamat</h1>
            <a href="{{ route('guest.profile.addresses.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            @include('guest.partials.profile-sidebar')

            <div class="col-lg-9">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="fw-bold mb-0">{{ $address->label ?: 'Alamat' }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('guest.profile.addresses.update', $address) }}" data-ajax="false">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Label</label>
                                    <input type="text" name="label" value="{{ old('label', $address->label) }}" class="form-control" placeholder="Rumah / Kantor">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nama Penerima</label>
                                    <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" class="form-control" placeholder="{{ $customer->full_name }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No. HP Penerima</label>
                                    <input type="text" name="phone" value="{{ old('phone', $address->phone) }}" class="form-control" placeholder="{{ $customer->phone }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Provinsi</label>
                                    <select id="province" name="province_code" class="form-select" required data-selected="{{ old('province_code', $address->province_code) }}">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <input type="hidden" name="province_name" value="{{ old('province_name', $address->province_name) }}" id="province_name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kabupaten/Kota</label>
                                    <select id="regency" name="regency_code" class="form-select" required data-selected="{{ old('regency_code', $address->regency_code) }}" disabled>
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                    <input type="hidden" name="regency_name" value="{{ old('regency_name', $address->regency_name) }}" id="regency_name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kecamatan</label>
                                    <select id="district" name="district_code" class="form-select" required data-selected="{{ old('district_code', $address->district_code) }}" disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    <input type="hidden" name="district_name" value="{{ old('district_name', $address->district_name) }}" id="district_name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Desa/Kelurahan</label>
                                    <select id="village" name="village_code" class="form-select" required data-selected="{{ old('village_code', $address->village_code) }}" disabled>
                                        <option value="">Pilih Desa</option>
                                    </select>
                                    <input type="hidden" name="village_name" value="{{ old('village_name', $address->village_name) }}" id="village_name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat Detail</label>
                                    <textarea name="address" class="form-control" rows="3" required>{{ old('address', $address->address) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="isActive" name="is_active" {{ old('is_active', $address->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">Jadikan alamat aktif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('guest.profile.addresses.index') }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        const provinceSelect = document.getElementById('province');
        const regencySelect = document.getElementById('regency');
        const districtSelect = document.getElementById('district');
        const villageSelect = document.getElementById('village');

        const provinceNameInput = document.getElementById('province_name');
        const regencyNameInput = document.getElementById('regency_name');
        const districtNameInput = document.getElementById('district_name');
        const villageNameInput = document.getElementById('village_name');

        if (!provinceSelect || !regencySelect || !districtSelect || !villageSelect) return;

        const cache = new Map();
        async function fetchJson(url) {
            if (cache.has(url)) return cache.get(url);
            const res = await fetch(url);
            if (!res.ok) throw new Error('Fetch failed: ' + res.status);
            const json = await res.json();
            cache.set(url, json);
            return json;
        }

        function resetSelect(select, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = true;
        }

        function setLoading(select) {
            select.innerHTML = '<option value="">Loading...</option>';
            select.disabled = true;
        }

        function fillOptions(select, placeholder, items) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach((it) => {
                const opt = document.createElement('option');
                opt.value = it.code;
                opt.textContent = it.name;
                select.appendChild(opt);
            });
            select.disabled = false;
        }

        function syncName(select, hiddenInput) {
            const opt = select.options[select.selectedIndex];
            hiddenInput.value = opt && opt.value ? (opt.textContent || '') : '';
        }

        async function loadProvinces() {
            setLoading(provinceSelect);
            resetSelect(regencySelect, 'Pilih Kabupaten');
            resetSelect(districtSelect, 'Pilih Kecamatan');
            resetSelect(villageSelect, 'Pilih Desa');

            const json = await fetchJson('{{ url('/regions/provinces') }}');
            fillOptions(provinceSelect, 'Pilih Provinsi', json.data || []);
        }

        async function loadRegencies(provinceCode) {
            setLoading(regencySelect);
            resetSelect(districtSelect, 'Pilih Kecamatan');
            resetSelect(villageSelect, 'Pilih Desa');

            const json = await fetchJson(`{{ url('/regions/regencies') }}/${encodeURIComponent(provinceCode)}`);
            fillOptions(regencySelect, 'Pilih Kabupaten', json.data || []);
        }

        async function loadDistricts(regencyCode) {
            setLoading(districtSelect);
            resetSelect(villageSelect, 'Pilih Desa');

            const json = await fetchJson(`{{ url('/regions/districts') }}/${encodeURIComponent(regencyCode)}`);
            fillOptions(districtSelect, 'Pilih Kecamatan', json.data || []);
        }

        async function loadVillages(districtCode) {
            setLoading(villageSelect);

            const json = await fetchJson(`{{ url('/regions/villages') }}/${encodeURIComponent(districtCode)}`);
            fillOptions(villageSelect, 'Pilih Desa', json.data || []);
        }

        provinceSelect.addEventListener('change', async (e) => {
            const code = e.target.value;
            syncName(provinceSelect, provinceNameInput);
            resetSelect(regencySelect, 'Pilih Kabupaten');
            resetSelect(districtSelect, 'Pilih Kecamatan');
            resetSelect(villageSelect, 'Pilih Desa');
            regencyNameInput.value = '';
            districtNameInput.value = '';
            villageNameInput.value = '';

            if (!code) return;
            try {
                await loadRegencies(code);
            } catch (_) {
                resetSelect(regencySelect, 'Gagal load kabupaten');
            }
        });

        regencySelect.addEventListener('change', async (e) => {
            const code = e.target.value;
            syncName(regencySelect, regencyNameInput);
            resetSelect(districtSelect, 'Pilih Kecamatan');
            resetSelect(villageSelect, 'Pilih Desa');
            districtNameInput.value = '';
            villageNameInput.value = '';

            if (!code) return;
            try {
                await loadDistricts(code);
            } catch (_) {
                resetSelect(districtSelect, 'Gagal load kecamatan');
            }
        });

        districtSelect.addEventListener('change', async (e) => {
            const code = e.target.value;
            syncName(districtSelect, districtNameInput);
            resetSelect(villageSelect, 'Pilih Desa');
            villageNameInput.value = '';

            if (!code) return;
            try {
                await loadVillages(code);
            } catch (_) {
                resetSelect(villageSelect, 'Gagal load desa');
            }
        });

        villageSelect.addEventListener('change', () => {
            syncName(villageSelect, villageNameInput);
        });

        async function init() {
            try {
                await loadProvinces();
            } catch (_) {
                resetSelect(provinceSelect, 'Gagal load provinsi');
                return;
            }

            const provinceSelected = provinceSelect.dataset.selected || '';
            const regencySelected = regencySelect.dataset.selected || '';
            const districtSelected = districtSelect.dataset.selected || '';
            const villageSelected = villageSelect.dataset.selected || '';

            if (provinceSelected) {
                provinceSelect.value = provinceSelected;
                if (!provinceNameInput.value) syncName(provinceSelect, provinceNameInput);
                try {
                    await loadRegencies(provinceSelected);
                } catch (_) {
                    return;
                }
            }

            if (regencySelected) {
                regencySelect.value = regencySelected;
                if (!regencyNameInput.value) syncName(regencySelect, regencyNameInput);
                try {
                    await loadDistricts(regencySelected);
                } catch (_) {
                    return;
                }
            }

            if (districtSelected) {
                districtSelect.value = districtSelected;
                if (!districtNameInput.value) syncName(districtSelect, districtNameInput);
                try {
                    await loadVillages(districtSelected);
                } catch (_) {
                    return;
                }
            }

            if (villageSelected) {
                villageSelect.value = villageSelected;
                if (!villageNameInput.value) syncName(villageSelect, villageNameInput);
            }
        }

        init();
    })();
</script>
@endsection
