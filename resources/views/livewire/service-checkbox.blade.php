<div class="col-4">
    @if($selectedServices)
        <div class="form_input">
            <label class="form-label">Chọn dịch vụ<span
                    style="color:red;font-size:15px;font-weight:bold"> *</span></label>
            <div class="form-control">
                <div>
                    @foreach($selectedServices as $service)
                        <div class="form-check form-switch form-switch-success" dir="ltr">
                            <input class="form-check-input"
                                   type="checkbox" id="id_service_{{ $service['id'] }}"
                                   name="service[]" value="{{ $service['id'] }}">
                            <label style="padding-bottom: 5px" class="form-check-label"
                                   for="id_service_{{ $service['id'] }}">{{ $service['name'] }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
