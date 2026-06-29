<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('voyager.profile') }}">
                    <div class="logo-icon-container">
                        <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img src="{{ asset('images/mineria.png') }}" alt="Logo Icon">
                        @else
                            <img src="{{ Voyager::setting('image/icon.png') }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{Voyager::setting('admin.title', 'VOYAGER')}}</div>
                </a>
            </div><!-- .navbar-header -->

            <div class="panel widget center bgimage"
                 style="background-image:url({{ Voyager::image( Voyager::setting('admin.bg_image'), asset('images/banner.jpg') ) }}); background-size: cover; background-position: 0px;">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <img src="{{ asset('images/default.png') }}" class="avatar" alt="{{ Auth::user()->name }} avatar">
                    <h4>{{ ucwords(Auth::user()->name) }}</h4>
                    <p>{{ Auth::user()->email }}</p>

                    <a href="{{ route('voyager.profile') }}" class="btn btn-primary">{{ __('voyager::generic.profile') }}</a>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>
        <div id="adminmenu">
            @php
                $menuJson = menu('admin', '_json');
                if (!auth()->user()->hasPermission('browse_reportsform101s')) {
                    $reportRoutes = ['reports.form101s', 'reports.certificates'];
                    $items = collect(json_decode($menuJson, true))->filter(function ($item) use ($reportRoutes) {
                        // Quitar el padre "Reportes" por título
                        if (isset($item['title']) && strtolower(trim($item['title'])) === 'reportes') {
                            return false;
                        }
                        // Quitar ítems con ruta de reporte
                        if (!empty($item['route']) && in_array($item['route'], $reportRoutes)) {
                            return false;
                        }
                        // Quitar ítems con URL de reporte
                        if (!empty($item['url']) && strpos($item['url'], 'reports') !== false) {
                            return false;
                        }
                        return true;
                    })->values();
                    $menuJson = json_encode($items);
                }
            @endphp
            <admin-menu :items="{{ $menuJson }}"></admin-menu>
        </div>

    </nav>
</div>
