@extends('guest.layouts.app')

@section('title', 'Alamat Saya - PAS Market')

@section('content')
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('guest.profile.index') }}">Profil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Alamat</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <h1 class="h3 fw-bold text-secondary mb-0">Alamat Saya</h1>
            <a href="{{ route('guest.profile.index') }}" class="btn btn-outline-secondary btn-sm">
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
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="fw-bold mb-0">Tambah Alamat</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('guest.profile.addresses.store') }}" data-ajax="false">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Label</label>
                                    <input type="text" name="label" value="{{ old('label') }}" class="form-control" placeholder="Rumah / Kantor">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nama Penerima</label>
                                    <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" class="form-control" placeholder="{{ $customer->full_name }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No. HP Penerima</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="{{ $customer->phone }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Provinsi</label>
                                    <select id="province" name="province_code" class="form-select" required data-selected="{{ old('province_code') }}">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <input type="hidden" name="province_name" value="{{ old('province_name') }}" id="province_name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kabupaten/Kota</label>
                                    <select id="regency" name="regency_code" class="form-select" required data-selected="{{ old('regency_code') }}" disabled>
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                    <input type="hidden" name="regency_name" value="{{ old('regency_name') }}" id="regency_name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kecamatan</label>
                                    <select id="district" name="district_code" class="form-select" required data-selected="{{ old('district_code') }}" disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    <input type="hidden" name="district_name" value="{{ old('district_name') }}" id="district_name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Desa/Kelurahan</label>
                                    <select id="village" name="village_code" class="form-select" required data-selected="{{ old('village_code') }}" disabled>
                                        <option value="">Pilih Desa</option>
                                    </select>
                                    <input type="hidden" name="village_name" value="{{ old('village_name') }}" id="village_name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat Detail</label>
                                    <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="isActive" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">Jadikan alamat aktif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Simpan Alamat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Daftar Alamat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse($addresses as $addr)
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-bold">
                                                    {{ $addr->label ?: 'Alamat' }}
                                                    @if($addr->is_active)
                                                        <span class="badge bg-success ms-2">Aktif</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $addr->recipient_name ?: $customer->full_name }}
                                                    @if($addr->phone)
                                                        · {{ $addr->phone }}
                                                    @endif
                                                </div>
                                                <div class="mt-2">{{ $addr->full_address }}</div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                @if(! $addr->is_active)
                                                    <form method="POST" action="{{ route('guest.profile.addresses.set-active', $addr) }}" data-ajax="false">
                                                        @csrf
                                                        <button class="btn btn-outline-success btn-sm" type="submit">Aktifkan</button>
                                                    </form>
                                                @endif

                                                <a class="btn btn-outline-secondary btn-sm" href="{{ route('guest.profile.addresses.edit', $addr) }}">
                                                    Edit
                                                </a>

                                                <form method="POST" action="{{ route('guest.profile.addresses.destroy', $addr) }}" data-ajax="false" onsubmit="return confirm('Hapus alamat ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm" type="submit">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-muted text-center py-4">Belum ada alamat.</div>
                            @endforelse
                        </div>
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
            provinceNameInput.value = '';
            regencyNameInput.value = '';
            districtNameInput.value = '';
            villageNameInput.value = '';

            const json = await fetchJson('{{ url('/regions/provinces') }}');
            fillOptions(provinceSelect, 'Pilih Provinsi', json.data || []);
        }

        async function loadRegencies(provinceCode) {
            setLoading(regencySelect);
            resetSelect(districtSelect, 'Pilih Kecamatan');
            resetSelect(villageSelect, 'Pilih Desa');
            regencyNameInput.value = '';
            districtNameInput.value = '';
            villageNameInput.value = '';

            const json = await fetchJson(`{{ url('/regions/regencies') }}/${encodeURIComponent(provinceCode)}`);
            fillOptions(regencySelect, 'Pilih Kabupaten', json.data || []);
        }

        async function loadDistricts(regencyCode) {
            setLoading(districtSelect);
            resetSelect(villageSelect, 'Pilih Desa');
            districtNameInput.value = '';
            villageNameInput.value = '';

            const json = await fetchJson(`{{ url('/regions/districts') }}/${encodeURIComponent(regencyCode)}`);
            fillOptions(districtSelect, 'Pilih Kecamatan', json.data || []);
        }

        async function loadVillages(districtCode) {
            setLoading(villageSelect);
            villageNameInput.value = '';

            const json = await fetchJson(`{{ url('/regions/villages') }}/${encodeURIComponent(districtCode)}`);
            fillOptions(villageSelect, 'Pilih Desa', json.data || []);
        }

        provinceSelect.addEventListener('change', async (e) => {
            const code = e.target.value;
            syncName(provinceSelect, provinceNameInput);
            resetSelect(regencySelect, 'Pilih Kabupaten');
            resetSelect(districtSelect, 'Pilih Kecamatan');
            resetSelect(villageSelect, 'Pilih Desa');

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
                syncName(provinceSelect, provinceNameInput);
                try {
                    await loadRegencies(provinceSelected);
                } catch (_) {
                    return;
                }
            }

            if (regencySelected) {
                regencySelect.value = regencySelected;
                syncName(regencySelect, regencyNameInput);
                try {
                    await loadDistricts(regencySelected);
                } catch (_) {
                    return;
                }
            }

            if (districtSelected) {
                districtSelect.value = districtSelected;
                syncName(districtSelect, districtNameInput);
                try {
                    await loadVillages(districtSelected);
                } catch (_) {
                    return;
                }
            }

            if (villageSelected) {
                villageSelect.value = villageSelected;
                syncName(villageSelect, villageNameInput);
            }
        }

        init();
    })();
</script>
@endsection
