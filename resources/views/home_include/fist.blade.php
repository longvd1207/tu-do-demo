<div class="row">
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate bg-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-bold text-white text-truncate mb-0">
                            Tông doanh thu tháng</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                            {{ number_format($total, 0, '.', '.') . 'đ' }}
                            <span class="counter-value"></span>
                        </h4>
                        <a style="color: #ffffff00" href="#" class="">Xem chi
                            tiết</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="ri-exchange-dollar-line"></i>
                            {{-- <i class="ri-community-fill"> </i> --}}
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate bg-secondary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-bold text-white text-truncate mb-0">
                            Lượt mua vé thành công</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                            {{ $countOrder }} Lượt
                            <span class="counter-value" data-target="36894"></span>
                        </h4>
                        <a href="#" class="text-decoration-underline text-white">Xem chi
                            tiết</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="ri-ticket-2-line"></i>
                            {{-- <i class="ri-team-line"> </i> --}}
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate bg-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-bold text-white text-truncate mb-0">
                            Số lượng vé bán ra</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                            {{ $countTicket }} Vé<span class="counter-value"></span>
                        </h4>
                        <a href="#" class="text-decoration-underline text-white">Xem
                            chi tiết</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="ri-shopping-cart-line"> </i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate bg-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-bold text-white text-truncate mb-0">
                            Số lượng hoạt động trên hệ thống</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                            {{ $countTicketType }} Loại vé
                            <span class="counter-value"></span>
                        </h4>
                        <a href="3" class="text-decoration-underline text-white">Xem
                            chi tiết</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="ri-coupon-3-line"></i>
                            {{-- <i class="ri-history-line"> </i> --}}
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row-->
