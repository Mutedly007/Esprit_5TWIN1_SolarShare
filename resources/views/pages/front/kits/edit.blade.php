{{-- resources/views/pages/front/kits/edit.blade.php --}}
@extends('layouts.solartec')

@section('title', __('messages.kits.edit_page_title', ['title' => $energyKit->title]))
@section('meta_description', __('messages.kits.edit_meta_desc'))

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">{{ __('messages.nav.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('energy-kits.index') }}" style="color: #0F5C6B; text-decoration: none;">{{ __('messages.nav.packs') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('energy-kits.show', $energyKit) }}" style="color: #0F5C6B; text-decoration: none;">{{ $energyKit->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">{{ __('messages.common.edit') }}</li>
            </ol>
        </nav>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    
                    <div class="mb-4">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1 mb-2" style="background: rgba(255, 176, 32, 0.15); color: #C2410C; font-size: 0.75rem;">
                            ✏️ {{ __('messages.kits.edit_badge') }}
                        </span>
                        <h1 class="h3 fw-bold text-dark mb-1">{{ __('messages.kits.edit_heading') }}</h1>
                        <p class="text-muted small mb-0">
                            {{ __('messages.kits.edit_subheading') }}
                        </p>
                    </div>

                    <form action="{{ route('energy-kits.update', $energyKit) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                {{ __('messages.kits.field_title') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $energyKit->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Target Use & Discount -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="target_use" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                    {{ __('messages.kits.field_target_use') }} <span class="text-danger">*</span>
                                </label>
                                <select name="target_use" id="target_use" class="form-select rounded-3 py-2 @error('target_use') is-invalid @enderror" required>
                                    <option value="vanlife" {{ old('target_use', $energyKit->target_use) === 'vanlife' ? 'selected' : '' }}>🚐 {{ __('messages.kits.use_vanlife') }}</option>
                                    <option value="camping" {{ old('target_use', $energyKit->target_use) === 'camping' ? 'selected' : '' }}>⛺ {{ __('messages.kits.use_camping') }}</option>
                                    <option value="chantier" {{ old('target_use', $energyKit->target_use) === 'chantier' ? 'selected' : '' }}>🔨 {{ __('messages.kits.use_chantier') }}</option>
                                    <option value="urgence" {{ old('target_use', $energyKit->target_use) === 'urgence' ? 'selected' : '' }}>🚨 {{ __('messages.kits.use_urgence') }}</option>
                                    <option value="evenement" {{ old('target_use', $energyKit->target_use) === 'evenement' ? 'selected' : '' }}>🎉 {{ __('messages.kits.use_evenement') }}</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="discount_percentage" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                    {{ __('messages.kits.field_discount') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="discount_percentage" 
                                           id="discount_percentage" 
                                           class="form-control rounded-start py-2 @error('discount_percentage') is-invalid @enderror" 
                                           min="0" 
                                           max="90" 
                                           step="1" 
                                           value="{{ old('discount_percentage', $energyKit->discount_percentage) }}" 
                                           required>
                                    <span class="input-group-text rounded-end bg-light fw-bold">%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                {{ __('messages.kits.field_description') }}
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control rounded-3 @error('description') is-invalid @enderror">{{ old('description', $energyKit->description) }}</textarea>
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                {{ __('messages.kits.field_image') }}
                            </label>
                            <input type="file" name="image" id="image" class="form-control rounded-3" accept="image/*">
                            @if($energyKit->image_url)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ $energyKit->image_url }}" alt="{{ __('messages.kits.current_image') }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    <small class="text-muted">{{ __('messages.kits.current_image') }}</small>
                                </div>
                            @endif
                        </div>

                        <!-- Bundled Equipments (N-N) -->
                        <div class="mb-4 p-4 rounded-3 border" style="background: #FAFBFD;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">{{ __('messages.kits.bundled_section_title') }}</h5>
                                    <small class="text-muted">{{ __('messages.kits.bundled_edit_sub') }}</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addEquipmentRow()">
                                    + {{ __('messages.kits.add_equipment_btn') }}
                                </button>
                            </div>

                            <div id="equipment-rows-container">
                                @foreach($energyKit->equipment as $idx => $item)
                                    <div class="row g-2 align-items-end mb-3 equipment-row p-3 rounded-3 bg-white border">
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold small text-dark mb-1">{{ __('messages.kits.col_equipment') }}</label>
                                            <select name="items[{{ $idx }}][equipment_id]" class="form-select rounded-3 py-2" required>
                                                @foreach($equipments as $eq)
                                                    <option value="{{ $eq->id }}" {{ $item->id === $eq->id ? 'selected' : '' }}>
                                                        {{ $eq->name }} ({{ $eq->category?->name }}) — {{ number_format($eq->daily_rate, 2) }} {{ __('messages.kits.tnd_per_day_short') }} {{ auth()->user()->isAdmin() && $eq->user ? '— ' . __('messages.kits.by_owner') . ' ' . $eq->user->name : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2 col-4">
                                            <label class="form-label fw-bold small text-dark mb-1">{{ __('messages.kits.modal_qty_label') }}</label>
                                            <input type="number" name="items[{{ $idx }}][quantity]" class="form-control rounded-3 py-2" min="1" max="20" value="{{ $item->pivot->quantity }}" required>
                                        </div>

                                        <div class="col-md-4 col-6">
                                            <label class="form-label fw-bold small text-dark mb-1">{{ __('messages.kits.field_options_cables') }}</label>
                                            <input type="text" name="items[{{ $idx }}][options]" class="form-control rounded-3 py-2" value="{{ $item->pivot->options }}" placeholder="{{ __('messages.kits.field_options_placeholder') }}">
                                        </div>

                                        <div class="col-md-1 col-2 text-end">
                                            <button type="button" class="btn btn-outline-danger rounded-3 py-2 w-100" onclick="removeEquipmentRow(this)" title="{{ __('messages.kits.remove_row_title') }}">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Active Toggle -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $energyKit->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="is_active">
                                {{ __('messages.kits.active_visible_label') }}
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('energy-kits.show', $energyKit) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                {{ __('messages.common.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold flex-grow-1" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1rem;">
                                💾 {{ __('messages.kits.submit_update') }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</section>

@push('scripts')
<script>
    let rowIndex = {{ $energyKit->equipment->count() + 1 }};
    const editI18n = {
        equipment: @json(__('messages.kits.col_equipment')),
        quantity: @json(__('messages.kits.modal_qty_label')),
        options: @json(__('messages.kits.field_options_cables')),
        optionsPlaceholder: @json(__('messages.kits.field_options_placeholder')),
        removeTitle: @json(__('messages.kits.remove_row_title')),
        minOneAlert: @json(__('messages.kits.err_min_one_eq')),
    };

    function addEquipmentRow() {
        const container = document.getElementById('equipment-rows-container');
        const firstRow = container.querySelector('.equipment-row');
        const selectOptions = firstRow.querySelector('select').innerHTML;

        const newRow = document.createElement('div');
        newRow.className = 'row g-2 align-items-end mb-3 equipment-row p-3 rounded-3 bg-white border';
        newRow.innerHTML = `
            <div class="col-md-5">
                <label class="form-label fw-bold small text-dark mb-1">${editI18n.equipment}</label>
                <select name="items[${rowIndex}][equipment_id]" class="form-select rounded-3 py-2" required>
                    ${selectOptions}
                </select>
            </div>
            <div class="col-md-2 col-4">
                <label class="form-label fw-bold small text-dark mb-1">${editI18n.quantity}</label>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control rounded-3 py-2" min="1" max="20" value="1" required>
            </div>
            <div class="col-md-4 col-6">
                <label class="form-label fw-bold small text-dark mb-1">${editI18n.options}</label>
                <input type="text" name="items[${rowIndex}][options]" class="form-control rounded-3 py-2" placeholder="${editI18n.optionsPlaceholder}">
            </div>
            <div class="col-md-1 col-2 text-end">
                <button type="button" class="btn btn-outline-danger rounded-3 py-2 w-100" onclick="removeEquipmentRow(this)" title="${editI18n.removeTitle}">
                    &times;
                </button>
            </div>
        `;
        container.appendChild(newRow);
        rowIndex++;
    }

    function removeEquipmentRow(btn) {
        const container = document.getElementById('equipment-rows-container');
        if (container.querySelectorAll('.equipment-row').length > 1) {
            btn.closest('.equipment-row').remove();
        } else {
            alert(editI18n.minOneAlert);
        }
    }
</script>
@endpush
@endsection
