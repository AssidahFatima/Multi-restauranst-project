<!-- Home -->
<li>
@if (\Request::is('home'))
    <li class="active">
        @endif
        <a href="home">
            <i class="material-icons" style="color: #ff9800 ;">home</i>
            <span>{{$lang->get(0)}}</span>
        </a>
        @if (\Request::is('home'))
    </li>
    @endif
    </li>


    <!-- Foods -->
    <li>
    @if (\Request::is('foods') OR \Request::is('foodadd') OR \Request::is('extras') OR \Request::is('extrasgroupadd')
            OR \Request::is('foodsreviews') OR \Request::is('nutrition') OR \Request::is('nutritiongroupadd')
            OR \Request::is('categories') OR \Request::is('categoriesadd') OR \Request::is('categoriesedit')
            OR \Request::is('foodedit') OR \Request::is('extrasgroupedit') OR \Request::is('nutritiongroupedit')
            OR \Request::is('foodreviewsedit') OR \Request::is('foodreviewsadd') )
        <li class="active">
            @endif
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons" style="color: #ff5722;">cake</i>
                <span>{{$lang->get(1)}}</span>
            </a>
            <ul class="ml-menu">

                <!-- Categories -->
                @if ($userinfo->getUserPermission("Food::Categories::View") )
                    <li>
                    @if (\Request::is('categories') OR \Request::is('categoriesadd') OR \Request::is('categoriesedit'))
                        <li class="active">
                            @endif
                            <a href="categories">{{$lang->get(2)}}</a>
                            </a>
                            @if (\Request::is('categories') OR \Request::is('categoriesadd') OR \Request::is('categoriesedit'))
                        </li>
                        @endif
                        </li>
                    @endif

                    @if ($userinfo->getUserPermission("Food::Food::View"))
                        <li>
                        @if (\Request::is('foods') OR \Request::is('foodadd') OR \Request::is('foodedit'))
                            <li class="active">
                                @endif
                                <a href="foods">{{$lang->get(3)}}</a>
                                @if (\Request::is('foods') OR \Request::is('foodadd') OR \Request::is('foodedit'))
                            </li>
                            @endif
                            </li>
                        @endif

                        @if ($userinfo->getUserPermission("Food::ExtrasGroup::View"))
                            <li>
                            @if (\Request::is('extras') OR \Request::is('extrasgroupadd') OR \Request::is('extrasgroupedit'))
                                <li class="active">
                                    @endif
                                    <a href="extras">{{$lang->get(4)}}</a>
                                    @if (\Request::is('extras') OR \Request::is('extrasgroupadd') OR \Request::is('extrasgroupedit'))
                                </li>
                                @endif
                                </li>
                            @endif

                            @if ($userinfo->getUserPermission("Food::NutritionGroup::View"))
                                <li>
                                @if (\Request::is('nutrition') OR \Request::is('nutritiongroupadd') OR \Request::is('nutritiongroupedit'))
                                    <li class="active">
                                        @endif
                                        <a href="nutrition">{{$lang->get(5)}}</a>
                                        @if (\Request::is('nutrition') OR \Request::is('nutritiongroupadd') OR \Request::is('nutritiongroupedit'))
                                    </li>
                                    @endif
                                    </li>
                                @endif

                                @if ($userinfo->getUserPermission("Food::Reviews::View"))
                                    <li>
                                    @if (\Request::is('foodsreviews') OR \Request::is('foodreviewsadd') OR \Request::is('foodreviewsedit'))
                                        <li class="active">
                                            @endif
                                            <a href="foodsreviews">{{$lang->get(6)}}</a>
                                            @if (\Request::is('foodsreviews') OR \Request::is('foodreviewsadd') OR \Request::is('foodreviewsedit'))
                                        </li>
                                        @endif
                                        </li>
                                    @endif


            </ul>
        </li>
        @if (\Request::is('foods') OR \Request::is('foodadd') OR \Request::is('extras') OR \Request::is('extrasgroupadd')
                OR \Request::is('foodsreviews') OR \Request::is('nutrition') OR \Request::is('nutritiongroupadd')
                OR \Request::is('categories') OR \Request::is('categoriesadd') OR \Request::is('categoriesedit')
                OR \Request::is('foodedit') OR \Request::is('extrasgroupedit') OR \Request::is('nutritiongroupedit')
                OR \Request::is('foodreviewsedit') OR \Request::is('foodreviewsadd')
                )
        </li>
    @endif


    <!-- Restaurants -->
    <li>
    @if (\Request::is('restaurants') OR \Request::is('restaurantreviews') OR \Request::is('restaurantsedit')
                OR \Request::is('restorantsadd') OR \Request::is('restaurantsreviewedit') OR \Request::is('restorantsreviewadd')
               )
        <li class="active">
            @endif
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons" style="color: #673ab7 ;">restaurant</i>
                <span>{{$lang->get(8)}}</span>
            </a>
            <ul class="ml-menu">

                @if ($userinfo->getUserPermission("Restaurants::View"))
                    <li>
                    @if (\Request::is('restaurants') OR \Request::is('restaurantsedit') OR \Request::is('restorantsadd'))
                        <li class="active">
                            @endif
                            <a href="restaurants">{{$lang->get(8)}}</a>
                            @if (\Request::is('restaurants') OR \Request::is('restaurantsedit') OR \Request::is('restorantsadd'))
                        </li>
                        @endif
                        </li>
                    @endif

                    @if ($userinfo->getUserPermission("RestaurantReview::View"))
                        <li>
                        @if (\Request::is('restaurantreviews') OR \Request::is('restaurantsreviewedit') OR \Request::is('restorantsreviewadd'))
                            <li class="active">
                                @endif
                                <a href="restaurantreviews">{{$lang->get(9)}}</a>
                                @if (\Request::is('restaurantreviews') OR \Request::is('restaurantsreviewedit') OR \Request::is('restorantsreviewadd'))
                            </li>
                            @endif
                            </li>
                        @endif


            </ul>
        </li>
        @if (\Request::is('restaurants') OR \Request::is('restaurantreviews') OR \Request::is('restaurantsedit')
                OR \Request::is('restorantsadd') OR \Request::is('restaurantsreviewedit') OR \Request::is('restorantsreviewadd')
               )
        </li>
    @endif

    <!-- Users -->
    <li>
    @if (\Request::is('roles') OR \Request::is('users') OR \Request::is('permissions') OR \Request::is('useradd') OR \Request::is('useredit'))
        <li class="active">
            @endif
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons"style="color: #fff ;">groups</i>
                <span>{{$lang->get(11)}}</span>
            </a>
            <ul class="ml-menu">


                @if ($userinfo->getUserPermission("Users::View"))
                    <li>
                    @if (\Request::is('users') OR \Request::is('useradd') OR \Request::is('useredit'))
                        <li class="active">
                            @endif
                            <a href="users">{{$lang->get(11)}}</a>
                            @if (\Request::is('users') OR \Request::is('useradd') OR \Request::is('useredit'))
                        </li>
                        @endif
                        </li>
                    @endif

                    <li>
                    @if (\Request::is('roles'))
                        <li class="active">
                            @endif
                            <a href="roles">{{$lang->get(12)}}</a>
                            @if (\Request::is('roles'))
                        </li>
                        @endif
                        </li>

                        <li>
                        @if (\Request::is('permissions'))
                            <li class="active">
                                @endif
                                <a href="permissions">{{$lang->get(13)}}</a>
                                @if (\Request::is('permissions'))
                            </li>
                            @endif
                            </li>


            </ul>
        </li>
        @if (\Request::is('foods') OR \Request::is('users') OR \Request::is('permissions') OR \Request::is('useradd') OR \Request::is('useredit'))
        </li>
    @endif



    <!-- Orders -->
    <li>
    @if (\Request::is('orders') OR \Request::is('ordersstatuses') OR \Request::is('ordersedit'))
        <li class="active">
            @endif
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons"style="color: #9c27b0 ;">cases</i>
                <span>{{$lang->get(14)}}</span>
            </a>
            <ul class="ml-menu">

                @if ($userinfo->getUserPermission("Orders::View"))
                    <li>
                    @if (\Request::is('orders') OR \Request::is('ordersedit'))
                        <li class="active">
                            @endif
                            <a href="orders">{{$lang->get(14)}}</a>
                            @if (\Request::is('orders') OR \Request::is('ordersedit'))
                        </li>
                        @endif
                        </li>
                    @endif

                    <li>
                    @if (\Request::is('ordersstatuses'))
                        <li class="active">
                            @endif
                            <a href="ordersstatuses">{{$lang->get(15)}}</a>
                            @if (\Request::is('ordersstatuses'))
                        </li>
                        @endif
                        </li>

            </ul>
        </li>
        @if (\Request::is('orders') OR \Request::is('ordersstatuses') OR \Request::is('ordersedit') OR \Request::is('toprestaurants'))
        </li>
    @endif

    <!-- Reports -->

    <li>
    @if (\Request::is('mostpopular')  OR \Request::is('mostpurchase') OR \Request::is('toprestaurants'))
        <li class="active">
            @endif
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons" style="color: #2196f3 ;">summarize</i>
                <span>{{$lang->get(16)}}</span>
            </a>
            <ul class="ml-menu">


                <li>
                @if (\Request::is('mostpopular'))
                    <li class="active">
                        @endif
                        <a href="mostpopular">{{$lang->get(17)}}</a>
                        @if (\Request::is('mostpopular'))
                    </li>
                    @endif
                    </li>

                    <li>
                    @if (\Request::is('mostpurchase') )
                        <li class="active">
                            @endif
                            <a href="mostpurchase">{{$lang->get(18)}}</a>
                            @if (\Request::is('mostpurchase'))
                        </li>
                        @endif
                        </li>


                        <li>
                        @if (\Request::is('toprestaurants') )
                            <li class="active">
                                @endif
                                <a href="toprestaurants">{{$lang->get(19)}}</a>
                                @if (\Request::is('toprestaurants'))
                            </li>
                            @endif
                            </li>

            </ul>
        </li>
        @if (\Request::is('mostpopular')  OR \Request::is('mostpurchase') OR \Request::is('toprestaurants'))
        </li>
    @endif

    <!-- City -->

    <li>
    @if (\Request::is('drivers'))
        <li class="active">
            @endif
            <a href="city">
                <i class="material-icons" style="color: #009688 ;">flag</i>
                <span>{{$lang->get(637)}}</span>
            </a>
            @if (\Request::is('city'))
        </li>
        @endif
        </li>

    <!-- Drivers -->


    <li>
        @if (\Request::is('drivers') OR \Request::is('driversDetails'))
            <li class="active">
                @endif
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons" style="color: #e91e63;">directions_car</i>
                    <span>{{$lang->get(20)}}</span>
                </a>
                <ul class="ml-menu">

                    @if ($userinfo->getUserPermission("Orders::View"))
                        <li>
                        @if (\Request::is('drivers') OR \Request::is('ordersedit'))
                            <li class="active">
                                @endif
                                <a href="drivers">{{$lang->get(646)}}</a>
                                @if (\Request::is('orders') OR \Request::is('ordersedit'))
                            </li>
                            @endif
                            </li>
                        @endif

                        <li>
                        @if (\Request::is('driversDetails'))
                            <li class="active">
                                @endif
                                <a href="driversDetails">{{$lang->get(647)}}</a>
                                @if (\Request::is('driversdetails'))
                            </li>
                            @endif
                            </li>

                </ul>
            </li>
            @if (\Request::is('orders') OR \Request::is('driversDetails') OR \Request::is('ordersedit') OR \Request::is('toprestaurants'))
            </li>
        @endif


        <!-- Coupons -->

        <li>
        @if (\Request::is('coupons'))
            <li class="active">
                @endif
                <a href="coupons">
                    <i class="material-icons"  style="color: #009688;">card_giftcard</i>
                    <span>{{$lang->get(21)}}</span>
                </a>
                @if (\Request::is('coupons'))
            </li>
            @endif
            </li>

            <!-- Notifications -->

            <li>
            @if (\Request::is('notify') OR \Request::is('sendmsg'))
                <li class="active">
                    @endif
                    <a href="notify">
                        <i class="material-icons" style="color: #ff9800;" >notifications</i>
                        <span>{{$lang->get(22)}}</span>
                    </a>
                    @if (\Request::is('notify') OR \Request::is('sendmsg'))
                </li>
                @endif
                </li>


                <!-- chat -->

                @if ($userinfo->getUserPermission("Chat::View") )
                    <li>
                    @if (\Request::is('chat'))
                        <li class="active">
                            @endif
                            <a href="chat">
                                <i class="material-icons" style="color: #009688;">sms</i>
                                <span>{{$lang->get(23)}}</span>
                            </a>
                            @if (\Request::is('chat') )
                        </li>
                        @endif
                        </li>
                    @endif

                    <!-- wallet -->
                    @if ($userinfo->getUserPermission("Wallet::View") )
                        <li>
                        @if (\Request::is('wallet'))
                            <li class="active">
                                @endif
                                <a href="wallet">
                                    <i class="material-icons" style="color: #ff5722;">credit_card</i>
                                    <span>{{$lang->get(24)}}</span>
                                </a>
                                @if (\Request::is('wallet') )
                            </li>
                            @endif
                            </li>
                        @endif

                        <!-- Documents -->
                        @if ($userinfo->getUserPermission("Documents::View") )
                            <li>
                            @if (\Request::is('documents'))
                                <li class="active">
                                    @endif
                                    <a href="documents">
                                        <i class="material-icons" style="color:#009688;">description</i>
                                        <span>{{$lang->get(497)}}</span>
                                    </a>
                                    @if (\Request::is('documents') )
                                </li>
                                @endif
                                </li>
                            @endif

                            <!-- Banner -->
                            @if ($userinfo->getUserPermission("Banners::View") )
                                <li>
                                @if (\Request::is('banners'))
                                    <li class="active">
                                        @endif
                                        <a href="banners">
                                            <i class="material-icons" style="color: #9c27b0;">bookmarks</i>
                                            <span>{{$lang->get(505)}}</span>  {{--Banner--}}
                                        </a>
                                        @if (\Request::is('banners') )
                                    </li>
                                    @endif
                                    </li>
                                @endif


                                <li class="header">{{$lang->get(27)}}</li>  {{--Settings--}}

                                <!-- Media Library -->
                                <li>
                                @if (\Request::is('media'))
                                    <li class="active">
                                        @endif
                                        <a href="media">
                                            <i class="material-icons" style="color: #ff9800;">image</i>
                                            <span>{{$lang->get(25)}}</span>
                                        </a>
                                        @if (\Request::is('media'))
                                    </li>
                                    @endif
                                    </li>

                                    <!-- FAQ -->
                                    @if ($userinfo->getUserPermission("Faq::View"))
                                        <li>
                                        @if (\Request::is('faq'))
                                            <li class="active">
                                                @endif
                                                <a href="faq">
                                                    <i class="material-icons" style="color: #2196f3;">help</i>
                                                    <span>{{$lang->get(26)}}</span>
                                                </a>
                                                @if (\Request::is('faq'))
                                            </li>
                                            @endif
                                            </li>
                                        @endif

                                        <!-- Settings -->

                                        @if ($userinfo->getUserPermission("Settings::ChangeSettings"))
                                            <li>
                                            @if (\Request::is('payments') OR \Request::is('settings') OR \Request::is('currencies'))
                                                <li class="active">
                                                    @endif
                                                    <a href="javascript:void(0);" class="menu-toggle">
                                                        <i class="material-icons"style="color: #9c27b0;">settings</i>
                                                        <span>{{$lang->get(27)}}</span>
                                                    </a>
                                                    <ul class="ml-menu">

                                                        <li>
                                                        @if (\Request::is('settings'))
                                                            <li class="active">
                                                                @endif
                                                                <a href="settings">{{$lang->get(27)}}</a>
                                                                @if (\Request::is('settings'))
                                                            </li>
                                                            @endif
                                                            </li>

                                                            <li>
                                                            @if (\Request::is('currencies'))
                                                                <li class="active">
                                                                    @endif
                                                                    <a href="currencies">{{$lang->get(28)}}</a>
                                                                    @if (\Request::is('currencies'))
                                                                </li>
                                                                @endif
                                                                </li>

                                                                <li>
                                                                @if (\Request::is('payments'))
                                                                    <li class="active">
                                                                        @endif
                                                                        <a href="payments">{{$lang->get(29)}}</a>
                                                                        @if (\Request::is('payments'))
                                                                    </li>
                                                                    @endif
                                                                    </li>

                                                    </ul>
                                                </li>
                                                @if (\Request::is('payments') OR \Request::is('settings') OR \Request::is('currencies'))
                                                </li>
                                            @endif
                                        @endif


    @if ($userinfo->getUserPermission("Settings::ChangeSettings"))
        <li>
        @if (\Request::is('caLayout') OR \Request::is('caLayoutColors') OR \Request::is('caTheme') OR \Request::is('caLayoutSizes') OR \Request::is('topfoods')
                OR \Request::is('toprestaurants2'))
            <li class="active">
        @endif
            <a href="javascript:void(0);" class="menu-toggle"><i class="material-icons" style="color: #009688;">settings</i>
                <span>{{$lang->get(30)}}</span></a>         {{--Customer App Settings--}}
            <ul class="ml-menu">

            {{--General--}}
            @include('elements.menuSubItem', array('text' => $lang->get(31), 'href' => "caTheme"))

            {{--Home Screen Layout--}}
            @include('elements.menuSubItem', array('text' => $lang->get(32), 'href' => "caLayout"))

            {{--Home Screen Colors--}}
            @include('elements.menuSubItem', array('text' => $lang->get(33), 'href' => "caLayoutColors"))

            {{--Home Screen Sizes--}}
            @include('elements.menuSubItem', array('text' => $lang->get(34), 'href' => "caLayoutSizes"))

            {{--Top Foods on Home Screen--}}
            @include('elements.menuSubItem', array('text' => $lang->get(7), 'href' => "topfoods"))

            {{--Top Restaurants on Home Screen--}}
            @include('elements.menuSubItem', array('text' => $lang->get(10), 'href' => "toprestaurants2"))

            </ul>
        </li>

        <!-- Web Site Settings -->
        @include('elements.menuItem', array('text' => $lang->get(609), 'href' => "webSettings", 'icon' => 'settings'))
    @endif

    <!-- Logging -->
    @if ($userinfo->getUserPermission("Logging::View"))
        @include('elements.menuItem', array('text' => $lang->get(35), 'href' => "logging", 'icon' => 'format_align_justify'))
    @endif
