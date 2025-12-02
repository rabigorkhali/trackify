<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{url('/')}}" class="app-brand-link">
            @if(getConfigTableData()?->logo)
                <span class="app-brand-logo demo">
               <img src="{{asset(getConfigTableData()?->logo)}}" class="img-fluid">
           </span>
            @endif
            <span class="app-brand-text demo menu-text fw-bold fs-6">{{getConfigTableData()?->company_name}}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">

        @foreach(modules() as $module)
            @if (hasPermissionOnModule($module))
                @if ($module['hasSubmodules'])
                    <li class="menu-item
                        @foreach ($module['routeIndexNameMultipleSubMenu']??[] as $rawRouteName)
                            @php
                                try {
                                    $routeUrl = route($rawRouteName);
                                } catch (\Exception $e) {
                                    $routeUrl = '';
                                }
                            @endphp
                            @if ($routeUrl && str_contains(url()->current(), $routeUrl))
                                open
                           @endif
                        @endforeach
                    ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                            {!! $module['icon'] !!}
                            <div>&nbsp; {{__($module['name'])}}</div>
                        </a>
                        <ul class="menu-sub">
                            @foreach ($module['submodules'] as $subModule)
                                @if($subModule['hasSubmodules'] ?? false)
                                    {{-- Handle nested submodules (third level) --}}
                                    <li class="text-decoration-none menu-item
                                        @foreach ($subModule['routeIndexNameMultipleSubMenu']??[] as $rawRouteName)
                                            @php
                                                try {
                                                    $routeUrl = route($rawRouteName);
                                                } catch (\Exception $e) {
                                                    $routeUrl = '';
                                                }
                                            @endphp
                                            @if ($routeUrl && str_contains(url()->current(), $routeUrl))
                                                open
                                           @endif
                                        @endforeach
                                    ">
                                        <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                                            {!! $subModule['icon'] ?? '' !!}
                                            <div>&nbsp; {{__($subModule['name'])}}</div>
                                        </a>
                                        <ul class="menu-sub">
                                            @foreach ($subModule['submodules'] ?? [] as $childModule)
                                                @if(isset($childModule['route']) && hasPermission($childModule['route']))
                                                    @if($childModule['manualIndexNameForActiveTab']??null)
                                                        <li class="text-decoration-none menu-item @if ('?'.request()->getQueryString()==$childModule['manualIndexNameForActiveTab']) active @endif">
                                                            <a href="{{ $childModule['manualIndexName']??route($childModule['routeIndexName']) }}"
                                                               class="menu-link text-decoration-none">
                                                                <div>{{__($childModule['name'])}}</div>
                                                            </a>
                                                        </li>
                                                    @else
                                                        @php
                                                            try {
                                                                $childRoute = $childModule['manualIndexName'] ?? route($childModule['routeIndexName']);
                                                                $childRouteForCheck = $childModule['manualIndexNameForActiveTab'] ?? route($childModule['routeIndexName']);
                                                            } catch (\Exception $e) {
                                                                $childRoute = '#';
                                                                $childRouteForCheck = '';
                                                            }
                                                        @endphp
                                                        <li class="text-decoration-none menu-item @if ($childRouteForCheck && str_contains(url()->current(), $childRouteForCheck)) active @endif">
                                                            <a href="{{ $childRoute }}"
                                                               class="menu-link text-decoration-none">
                                                                <div>{{__($childModule['name'])}}</div>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @elseif(isset($subModule['route']) && hasPermission($subModule['route']))
                                    {{-- Handle regular submodules (second level) --}}
                                    @if($subModule['manualIndexNameForActiveTab']??null)
                                        <li class="text-decoration-none menu-item @if ('?'.request()->getQueryString()==$subModule['manualIndexNameForActiveTab']) active @endif">
                                            <a href="{{ $subModule['manualIndexName']??route($subModule['routeIndexName']) }}"
                                               class="menu-link text-decoration-none">
                                                <div>{{__($subModule['name'])}}</div>
                                            </a>
                                        </li>
                                    @else
                                        @php
                                            try {
                                                $subModuleRoute = $subModule['manualIndexName'] ?? route($subModule['routeIndexName']);
                                                $subModuleRouteForCheck = $subModule['manualIndexNameForActiveTab'] ?? route($subModule['routeIndexName']);
                                            } catch (\Exception $e) {
                                                $subModuleRoute = '#';
                                                $subModuleRouteForCheck = '';
                                            }
                                        @endphp
                                        <li class="text-decoration-none menu-item @if ($subModuleRouteForCheck && str_contains(url()->current(), $subModuleRouteForCheck)) active @endif">
                                            <a href="{{ $subModuleRoute }}"
                                               class="menu-link text-decoration-none">
                                                <div>{{__($subModule['name'])}}</div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                            @endforeach
                        </ul>
                    </li>

                @else
                    <li class="menu-item @if (str_contains(url()->current(),route($module['routeIndexName']))) active @endif">
                        <a href="{{route($module['routeIndexName'])}}" class="menu-link text-decoration-none">
                            {!! $module['icon'] !!}
                            <div>&nbsp; {{__($module['name'])}}</div>
                        </a>
                    </li>
                @endif
            @endif
        @endforeach

        {{--        <li class="menu-item @if (str_contains(url()->current(),route('configs.index'))) open @endif">--}}
        {{--            <a href="javascript:void(0);" class="menu-link menu-toggle">--}}
        {{--                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>--}}
        {{--                <div>{{ __('Settings') }}</div>--}}
        {{--            </a>--}}
        {{--            <ul class="menu-sub">--}}
        {{--                <li class="menu-item @if (str_contains(url()->current(),route('configs.index'))) active @endif">--}}
        {{--                    <a href="{{ route('configs.index') }}" class="menu-link">--}}
        {{--                        <div>{{ __('Configs') }}</div>--}}
        {{--                    </a>--}}
        {{--                </li>--}}

        {{--            </ul>--}}
        {{--        </li>--}}
        {{--        <li class="menu-item @if (str_contains(url()->current(),route('users.index'))) open @endif">--}}
        {{--            <a href="javascript:void(0);" class="menu-link menu-toggle">--}}
        {{--                <i class="menu-icon tf-icons ti ti-users"></i>--}}
        {{--                <div>{{ __('User Management') }}</div>--}}
        {{--            </a>--}}
        {{--            <ul class="menu-sub">--}}
        {{--                <li class="menu-item @if (str_contains(url()->current(),route('users.index'))) active @endif">--}}
        {{--                    <a href="{{ route('users.index') }}" class="menu-link">--}}
        {{--                        <div>{{ __('Users') }}</div>--}}
        {{--                    </a>--}}
        {{--                </li>--}}
        {{--                <li class="menu-item @if (str_contains(url()->current(),route('roles.index'))) active @endif">--}}
        {{--                    <a href="{{ route('roles.index') }}" class="menu-link">--}}
        {{--                        <div>{{ __('Roles') }}</div>--}}
        {{--                    </a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
    </ul>
</aside>
