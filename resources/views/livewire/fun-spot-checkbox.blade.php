<div class="col-4">
    @if($selectedFunSpots)
        <div class="form_input">
            <label class="form-label">Chọn điểm vui chơi<span
                    style="color:red;font-size:15px;font-weight:bold"> *</span></label>
            <div class="form-control">
                <div>
                    @foreach($selectedFunSpots as $funSpot)
                        <div class="form-check form-switch form-switch-success" dir="ltr">
                            <input class="form-check-input"
                                   type="checkbox" id="id_funSpot_{{ $funSpot['id'] }}"
                                   name="funSpot[]" value="{{ $funSpot['id'] }}">
                            <label style="padding-bottom: 5px" class="form-check-label"
                                   for="id_funSpot_{{ $funSpot['id'] }}">{{ $funSpot['name'] }}
                            </label>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    @endif
</div>
