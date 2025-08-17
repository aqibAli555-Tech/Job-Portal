<div class="header">
    <nav class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
        <div class="container-fluid">
            <div class="navbar-identity">
                <a href="{{ url('/') }}" class="navbar-brand logo logo-title">
                    <img src="{{ url()->asset('icon/logo.png') }}" onclick="page_count('logo_click')" alt="{{ strtolower(config('settings.app.app_name')??'') }}" class=" main-logo"/>
                </a>
            </div>
        </div>
    </nav>
</div>