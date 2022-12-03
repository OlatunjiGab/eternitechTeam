<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
@php
$user = Auth::user();
if($user && !empty($user->profile_picture)) {
    $profileImage = asset("storage/images/".$user->profile_picture);
} else {
    $profileImage = asset('/default-profile.jpg');
}
@endphp
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ $profileImage }}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    {{--                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
                </div>
            </div>
        @endif

    <!-- search form (Optional) -->
        @if(LAConfigs::getByKey('sidebar_search'))
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..."/>
                    <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
                </div>
            </form>
    @endif
    <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">MODULES</li>
            <!-- Optionally, you can add icons to the links -->
            @if(Entrust::hasRole('SUPER_ADMIN'))
                <li><a href="{{ url(config('laraadmin.adminRoute')) }}"><i class='fa fa-home'></i> <span>Dashboard</span></a></li>
            @else
                <li><a href="{{ url(config('laraadmin.partnerRoute')) }}"><i class='fa fa-home'></i> <span>Dashboard</span></a></li>
            @endif
            @if(LAFormMaker::la_access("Projects", "create"))
                @if(Entrust::hasRole("SUPER_ADMIN"))
                    <li><a href="{{url('admin/warmProjects')}}"><i class='fa fa-area-chart'></i> <span>Warm Leads</span></a></li>
                @endif
            @endif
            @php
                $menuItems = Dwij\Laraadmin\Models\Menu::where("parent", 0)->orderBy('hierarchy', 'asc')->get();
            @endphp
            @if(Entrust::hasRole('SUPER_ADMIN'))
                @foreach ($menuItems as $menu)
                    @if($menu->type == "module")
                        @php
                            $temp_module_obj = Module::get($menu->name);
                        @endphp
                        @if(LAFormMaker::la_access($temp_module_obj->id))
                            @if(isset($module->id) && $module->name == $menu->name)
                                {!! LAHelper::print_menu($menu ,true) !!}
                            @else
                            {!! LAHelper::print_menu($menu) !!}
                            @endif
                        @endif
                    @else
                        {!! LAHelper::print_menu($menu) !!}
                    @endif
                @endforeach
                <li>
                    <a href="/horizon"><i class='fa fa-dashboard'></i> <span>Queue Monitoring</span></a>
                </li>
            @else
                @php
                    $leadInfoString = "<span class='question-popover fa fa-question-circle-o' data-toggle='popover' title='Leads' data-content='Partners Looking for an agency to assist you? see all the partners in our community, their skills, and their rates..'></span>";
                    $expertInfoString = "<span class='question-popover fa fa-question-circle-o' data-toggle='popover' title='Experts' data-content='Showcase the skills and experience of your team members. It becomes easy for the employer to hire them.'></span>";
                    $portfolioInfoString = "<span class='question-popover fa fa-question-circle-o' data-toggle='popover' title='Portfolios' data-content='Display about all your completed projects in detail. It helps employers to know about your experience and skills'></span>";
                    $routes = App\Models\PartnerAccess::where('user_id', Auth::user()->id)->get();
                    $routes_array = ['Portfolios'];
                    $premiumCrown = "<span class='question-popover fa fa-star' data-toggle='popover' title='Become a Premium Client' data-content='This option is for Premium Plan only. Contact support for more information.'></span>";
                    if(Auth::user()->canAccess()){
                        $routes_array = ['Portfolios','Popups', 'Messages'];
                        $premiumCrown = "";
                    }
                    else{
                        $routes_array = ['Popups', 'Messages'];
                    }
                    $menus = Dwij\Laraadmin\Models\Menu::whereIn("name",$routes_array)->get();
                @endphp
                @foreach($routes as $route)
                    @php
                        $menu = Dwij\Laraadmin\Models\Menu::where("name", $route->route)->first();
                        $premiumCrownString = '';
                        if (!Auth::user()->canAccess() && in_array($menu->name,['Prospects','Partners'])){
                            $premiumCrownString = $premiumCrown;
                        }
                        $isExpert = false;
                        $infoString = "";
                        if ($menu->name == "Experts"){
                            $isExpert = true;
                            $infoString = $expertInfoString;
                        }
                        if($route && $route->is_access == 0) {
                            if($menu->name == 'Leads') {
                                $menu->name = 'My '.$menu->name." ".$leadInfoString;
                            } else {
                                $menu->icon = "fa-lock";
                                $menu->url = '#';
                                $menu->name = 'All '.$menu->name;
                            }
                        } elseif ($route && $route->is_access == 1) {
                            if($menu->name == 'Leads') {
                                $menu->name = 'My '.$menu->name." ".$leadInfoString;
                            }
                           
                            elseif ($menu->name == 'Portfolios'){
                                $menu->name = $menu->name." ".$leadInfoString;
                            }
                            else {
                                $menu->name = 'My '.$menu->name." ".$premiumCrownString;
                            }
                        } elseif ($route && $route->is_access == 2) {
                            if($menu->name == 'Leads') {
                                $menu->name = 'All '.$menu->name." ".$leadInfoString;
                            } else {
                                $menu->name = 'All '.$menu->name." ".$premiumCrownString;
                            }
                        } else {
                            if($menu->name == 'Leads') {
                                $menu->name = 'My '.$menu->name." ".$leadInfoString;
                            } else {
                                $menu->icon = "fa-lock";
                                $menu->url = '#';
                                $menu->name = 'All '.$menu->name." ".$premiumCrownString;
                            }
                        }
                        if($isExpert) {
                            $menu->name = $menu->name." ". $infoString;
                        }
                    @endphp
                    {!! LAHelper::print_menu($menu) !!}
                @endforeach
                @foreach($menus as $menu)
                    @php
                        $infoString = "";
                        if($menu->name == "Portfolios") {
                            $infoString = $portfolioInfoString;
                        }
                        $menu->name = 'My '.$menu->name." ".$infoString;
                    @endphp
                    {!! LAHelper::print_menu($menu) !!}
                @endforeach
            @endif
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>

@if(Entrust::hasRole("PARTNER"))
    <div class="modal fade" id="LockModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Info</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">Your account is pending admin review.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $('.fa-lock').parent().click(function (e) {
                e.preventDefault();
                $('#LockModal').modal('show');
            });
            // $('.fa-star').parent().parent().click(function (e) {
            @if(!Auth::user()->canAccess())
                $('.fa-navicon').parent().click(function (e) {
                    e.preventDefault();
                    $('#disable-feature-popup').modal('show');
                });
                $('.fa-cube').parent().click(function (e) {
                    e.preventDefault();
                    $('#disable-feature-popup').modal('show');
                    $('#prospect-guideline-popup').modal('hide');
                });
                $('.fa-external-link').parent().click(function (e) {
                    e.preventDefault();
                    $('#disable-feature-popup').modal('show');
                });
            @endif
            // $('.fa-cube').parent().click(function (e) {
            //     e.preventDefault();
            //     $('#prospect-guideline-popup').modal('show');
            // });
        </script>
    @endpush
@endif