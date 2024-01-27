<div class="col-4">
    @if ($areas)
        <div class="form_input">
            <label class="form-label">Chọn khu vực <span style="color:red;font-size:15px;font-weight:bold"> *</span></label>
            <div class="form-control">
                <div>
                    @foreach ($areas as $area)
                        <div class="form-check form-switch form-switch-success" dir="ltr">
                            <input wire:model="selectedAreas" wire:click="getAreaByCheckbox" type="checkbox"
                                class="form-check-input" id="id_area_{{ $area['id'] }}" name="area[]"
                                value="{{ $area['id'] }}">
                            <label style="padding-bottom: 5px" class="form-check-label"
                                for="id_area_{{ $area['id'] }}">
                                {{ $area['name'] }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
