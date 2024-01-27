<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('home') }}" class="logo logo-dark">
            <img src="{{ url('assets/images/logo-light.png') }}" alt="" style="width: 210px;height: auto;">
        </a>
        <!-- Light Logo-->
        <a href="{{ route('home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ config('kztek_config.url_public') . 'assets/images/logo-sm.png' }}" alt=""
                    height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ url('images/logo_bao_son.png') }}" alt="" style="width: 90%;">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                @include('components.left-menu')
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<style>
    a.active {
        color: #fff !important;
        font-size: 18px !important;
        background-color: #0f1c2f !important;
    }

    a[aria-expanded='true'] {
        color: #fff !important;
    }

    .navbar-menu .navbar-nav .nav-link {
        font-size: 17px;
    }

    .navbar-menu .navbar-nav .nav-link:hover {
        font-size: 18px;
        color: #fff !important;
        background-color: #0f1c2f;
    }


    /* .nav-item{
        fon
    } */
</style>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
