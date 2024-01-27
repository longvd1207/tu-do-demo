@if (!empty($breadcrumb))
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex">
                @foreach ($breadcrumb as $key => $val)
                    <h4 class="mb-0">
                        <a style="color:#EE877C"
                            href="{{ !empty($val['route']) ? ($val['route'] == 'category.index' ? route($val['route'], ['type' => $type]) : route($val['route'])) : '#' }}">
                            {{ $val['title'] }}
                            @if ($key < count($breadcrumb) - 1)
                                <i class="ri-arrow-right-s-line"></i>
                            @endif
                        </a>
                    </h4>
                @endforeach
            </div>
        </div>
    </div>
@endif
