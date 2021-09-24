<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul class="page-sidebar-menu page-sidebar-menu-light" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
            <li class="heading">
                <a class="bold text-light" href="{{ url('/') }}"><i class="icon-action-undo"></i> Back to shop</a>
            </li>

            @php
                $current_url = Route::current();
                $urls = explode('/', $current_url->uri);
            @endphp
            @foreach ($seller_menus as $menu)

                @if ($menu['url'] == $urls[1])
                    @php
                        $open = 'open';
                        $active = 'active';
                    @endphp
                @else
                    @php
                        $open = '';
                        $active = '';
                    @endphp
                @endif

                @if (count($menu['children']) > 0 )
                <li class="start {{ $active }} {{ $open }}">
                    <a href="javascript:;">
                    <i class="{{ $menu['icon'] }}"></i>
                    <span class="title text-light">{{ $menu['name'] }}</span>
                    <span class="selected"></span>
                    <span class="arrow {{ $open }}"></span>
                    </a>

                    <ul class="sub-menu">
                        @foreach ($menu['children'] as $child)
                            @php
                                if(isset($urls[2]) && $urls[2] == $child->url){
                                    $ch_active = 'active';
                                } else {
                                    $ch_active = '';
                                }
                            @endphp
                        <li  class="{{ $ch_active }}">
                            <a href="{{ url('seller/'.$menu['url'].'/'.$child->url) }}">
                                <i class="{{ $child->icon }}"></i>
                                {{ $child->name }}
                            </a>
                        </li>
                        @endforeach

                    </ul>
                </li>
                @else
                <li class="start {{ $active }} {{ $open }}">
                    <a href="{{ url('seller/'.$menu['url']) }}">
                    <i class="{{ $menu['icon'] }}"></i>
                    <span class="title">{{ $menu['name'] }}</span>
                    <span class="selected"></span>
                    </a>
                </li>
                @endif

            @endforeach


        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
