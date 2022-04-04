@can('dashboard')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/dashboard*') ? 'active' : '' }}" href="{!! url('console/dashboard') !!}">
            @if($icons)<i class="nav-icon fa fa-dashboard"></i>@endif
            <p>{{trans('lang.dashboard')}}</p></a>
    </li>
@endcan

<li class="nav-header">{{trans('lang.app_management')}}</li>

@can('categories.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/categories*') ? 'active' : '' }}" href="{!! url('console/categories') !!}">
            @if($icons)<i class="nav-icon fa fa-folder"></i>@endif
                <p>{{trans('lang.category_plural')}}</p>
        </a>
    </li>
@endcan

@can('tags.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/tags*') ? 'active' : '' }}" href="{!! url('console/tags') !!}">
            @if($icons)<i class="nav-icon fa fa-tags"></i>@endif
            <p>{{trans('lang.tag_plural')}}</p>
        </a>
    </li>
@endcan

@can('authors.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/authors*') ? 'active' : '' }}" href="{!! url('console/authors') !!}">
            @if($icons)<i class="nav-icon fa fa-user-circle-o"></i>@endif
            <p>{{trans('lang.author_plural')}}</p>
        </a>
    </li>
@endcan

@can('publishers.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/publishers*') ? 'active' : '' }}" href="{!! url('console/publishers') !!}">
            @if($icons)<i class="nav-icon fa fa-newspaper-o"></i>@endif
            <p>{{trans('lang.publisher_plural')}}</p>
        </a>
    </li>
@endcan

@can('attributes.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/attributes*') ? 'active' : '' }}" href="{!! url('console/attributes') !!}">
            @if($icons)<i class="nav-icon fa fa-tag"></i>@endif
            <p>{{trans('lang.attribute_plural')}}</p>
        </a>
    </li>
@endcan

@can('books.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/books*') ? 'active' : '' }}" href="{!! url('console/books') !!}">
            @if($icons)<i class="nav-icon fa fa-book"></i>@endif
            <p>{{trans('lang.book_plural')}}</p>
        </a>
    </li>
@endcan

@can('articles.index')
    <li class="nav-item has-treeview {{ Request::is('articles*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('articles*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-newspaper-o"></i>@endif
            <p>{{trans('lang.article_plural')}}<i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @can('articles.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('articles*') ? 'active' : '' }}" href="{{ url('console/articles') }}">
                        @if($icons)<i class="nav-icon fa fa-newspaper-o"></i>@endif
                        <p>{{trans('lang.article_plural')}}</p>
                    </a>
                </li>
            @endcan

        </ul>
    </li>
@endcan

<li class="nav-header">{{trans('lang.app_setting')}}</li>
@can('medias')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('console/medias*') ? 'active' : '' }}" href="{!! url('console/medias') !!}">@if($icons)<i class="nav-icon fa fa-picture-o"></i>@endif
            <p>{{trans('lang.media_plural')}}</p></a>
    </li>
@endcan

@can('app-settings')
    <li class="nav-item has-treeview {{ Request::is('console/settings/mobile*') || Request::is('console/slides*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('console/settings/mobile*') || Request::is('console/slides*') ? 'active' : '' }}">
            @if($icons)<i class="nav-icon fa fa-mobile"></i>@endif
            <p>
                {{trans('lang.mobile_menu')}}
                <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        @hasrole('dev')
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{!! url('console/settings/mobile/globals') !!}" class="nav-link {{  Request::is('console/settings/mobile/globals*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-cog"></i> @endif
                        <p>{{trans('lang.app_setting_globals')}}<span class="right badge badge-danger">New</span></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{!! url('console/settings/mobile/colors') !!}" class="nav-link {{  Request::is('console/settings/mobile/colors*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-pencil"></i> @endif
                        <p>{{trans('lang.mobile_colors')}} <span class="right badge badge-danger">New</span></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{!! url('console/settings/mobile/home') !!}" class="nav-link {{  Request::is('console/settings/mobile/home*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-home"></i> @endif
                        <p>{{trans('lang.mobile_home')}}<span class="right badge badge-danger">New</span></p>
                    </a>
                </li>
            </ul>
        @endhasrole

    </li>
    <li class="nav-item has-treeview {{ (Request::is('console/settings*') ||
        Request::is('console/users*')) && !Request::is('console/settings/mobile*')
        ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{
        (Request::is('console/settings*') ||
         Request::is('console/users*')) && !Request::is('console/settings/mobile*')
          ? 'active' : '' }}"> @if($icons)<i class="nav-icon fa fa-cogs"></i>@endif
            <p>{{trans('lang.app_setting')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! url('console/settings/app/globals') !!}" class="nav-link {{  Request::is('console/settings/app/globals*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-cog"></i> @endif <p>{{trans('lang.app_setting_globals')}}</p>
                </a>
            </li>

            @can('users.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('console/users*') ? 'active' : '' }}" href="{!! url('console/users') !!}">
                        @if($icons)<i class="nav-icon fa fa-users"></i>@endif
                        <p>{{trans('lang.user_plural')}}</p>
                    </a>
                </li>
            @endcan
            @can('permissions.index')
                <li class="nav-item has-treeview {{ Request::is('console/settings/permissions*') || Request::is('console/settings/roles*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('console/settings/permissions*') || Request::is('console/settings/roles*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-user-secret"></i>@endif
                        <p>
                            {{trans('lang.permission_menu')}}
                            <i class="right fa fa-angle-left"></i>
                        </p></a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('console/settings/permissions') ? 'active' : '' }}" href="{!! url('console/settings/permissions') !!}">
                                @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                <p>{{trans('lang.permission_table')}}</p>
                            </a>
                        </li>
                        @can('permissions.create')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('console/settings/permissions/create') ? 'active' : '' }}" href="{!! url('console/settings/permissions/create') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.permission_create')}}</p>
                                </a>
                            </li>
                        @endcan
                        @can('roles.index')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('console/settings/roles') ? 'active' : '' }}" href="{!! url('console/settings/roles') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.role_table')}}</p>
                                </a>
                            </li>
                        @endcan
                        @can('roles.create')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('console/settings/roles/create') ? 'active' : '' }}" href="{!! url('console/settings/roles/create') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.role_create')}}</p>
                                </a>
                            </li>
                        @endcan
                    </ul>

                </li>
            @endcan

            <li class="nav-item">
                <a href="{!! url('console/settings/app/notifications') !!}" class="nav-link {{  Request::is('console/settings/app/notifications*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-bell"></i> @endif
                    <p>{{trans('lang.app_setting_notifications')}}</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/mail/smtp') !!}" class="nav-link {{ Request::is('console/settings/mail*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-envelope"></i> @endif
                    <p>{{trans('lang.app_setting_mail')}}</p>
                </a>
            </li>

        </ul>
    </li>
@endcan

