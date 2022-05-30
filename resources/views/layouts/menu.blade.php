@can('dashboard')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{!! url('dashboard') !!}">
            @if($icons)<i class="nav-icon fa fa-dashboard"></i>@endif
            <p>{{trans('lang.dashboard')}}</p></a>
    </li>
@endcan

<li class="nav-header">{{trans('lang.app_management')}}</li>

@can('categories.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{!! url('categories') !!}">
            @if($icons)<i class="nav-icon fa fa-folder"></i>@endif
                <p>{{trans('lang.category_plural')}}</p>
        </a>
    </li>
@endcan

@can('tags.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('tags*') ? 'active' : '' }}" href="{!! url('tags') !!}">
            @if($icons)<i class="nav-icon fa fa-tags"></i>@endif
            <p>{{trans('lang.tag_plural')}}</p>
        </a>
    </li>
@endcan

@can('authors.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('authors*') ? 'active' : '' }}" href="{!! url('authors') !!}">
            @if($icons)<i class="nav-icon fa fa-user-circle-o"></i>@endif
            <p>{{trans('lang.author_plural')}}</p>
        </a>
    </li>
@endcan

@can('publishers.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('publishers*') ? 'active' : '' }}" href="{!! url('publishers') !!}">
            @if($icons)<i class="nav-icon fa fa-newspaper-o"></i>@endif
            <p>{{trans('lang.publisher_plural')}}</p>
        </a>
    </li>
@endcan

@can('attributes.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('attributes*') ? 'active' : '' }}" href="{!! url('attributes') !!}">
            @if($icons)<i class="nav-icon fa fa-tag"></i>@endif
            <p>{{trans('lang.attribute_plural')}}</p>
        </a>
    </li>
@endcan

@can('books.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('books*') ? 'active' : '' }}" href="{!! url('books') !!}">
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
                    <a class="nav-link {{ Request::is('articles*') ? 'active' : '' }}" href="{{ url('articles') }}">
                        @if($icons)<i class="nav-icon fa fa-newspaper-o"></i>@endif
                        <p>{{trans('lang.article_plural')}}</p>
                    </a>
                </li>
            @endcan

            @can('articles.categories.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('articles/categories*') ? 'active' : '' }}" href="{{ url('articles/categories') }}">
                        @if($icons)<i class="nav-icon fa fa-folder"></i>@endif
                        <p>{{trans('lang.article_category_plural')}}</p>
                    </a>
                </li>
            @endcan

        </ul>
    </li>
@endcan

@can('reviews.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('reviews*') ? 'active' : '' }}" href="{!! url('reviews') !!}">
            @if($icons)<i class="nav-icon fa fa-comment"></i>@endif
            <p>{{trans('lang.review_plural')}}</p>
        </a>
    </li>
@endcan

@can('selections.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('selections*') ? 'active' : '' }}" href="{!! url('selections') !!}">
            @if($icons)<i class="nav-icon fa fa-archive"></i>@endif
            <p>{{trans('lang.selection_plural')}}</p>
        </a>
    </li>
@endcan

@can('rented-books.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('rented-books*') ? 'active' : '' }}" href="{!! url('rented-books') !!}">
            @if($icons)<i class="nav-icon fa fa-address-book"></i>@endif
            <p>{{trans('lang.rented-book_plural')}}</p>
        </a>
    </li>
@endcan

@can('readers.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('readers*') ? 'active' : '' }}" href="{!! url('readers') !!}">
            @if($icons)<i class="nav-icon fa fa-bookmark"></i>@endif
            <p>{{trans('lang.reader_plural')}}</p>
        </a>
    </li>
@endcan

<li class="nav-header">{{trans('lang.app_setting')}}</li>

@can('app-settings')
    <li class="nav-item has-treeview {{ Request::is('settings/mobile*') || Request::is('slides*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('settings/mobile*') || Request::is('slides*') ? 'active' : '' }}">
            @if($icons)<i class="nav-icon fa fa-mobile"></i>@endif
            <p>
                {{trans('lang.mobile_menu')}}
                <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        @hasrole('dev')
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{!! url('settings/mobile/globals') !!}" class="nav-link {{  Request::is('settings/mobile/globals*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-cog"></i> @endif
                        <p>{{trans('lang.app_setting_globals')}}<span class="right badge badge-danger">New</span></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{!! url('settings/mobile/colors') !!}" class="nav-link {{  Request::is('settings/mobile/colors*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-pencil"></i> @endif
                        <p>{{trans('lang.mobile_colors')}} <span class="right badge badge-danger">New</span></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{!! url('settings/mobile/home') !!}" class="nav-link {{  Request::is('settings/mobile/home*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-home"></i> @endif
                        <p>{{trans('lang.mobile_home')}}<span class="right badge badge-danger">New</span></p>
                    </a>
                </li>
            </ul>
        @endhasrole

    </li>
    <li class="nav-item has-treeview {{ (Request::is('settings*') ||
        Request::is('users*')) && !Request::is('settings/mobile*')
        ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{
        (Request::is('settings*') ||
         Request::is('users*')) && !Request::is('settings/mobile*')
          ? 'active' : '' }}"> @if($icons)<i class="nav-icon fa fa-cogs"></i>@endif
            <p>
                {{trans('lang.app_setting')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! url('settings/app/globals') !!}" class="nav-link {{  Request::is('settings/app/globals*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-cog"></i> @endif <p>{{trans('lang.app_setting_globals')}}</p>
                </a>
            </li>

            @can('users.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{!! url('users') !!}">
                        @if($icons)<i class="nav-icon fa fa-users"></i>@endif
                        <p>{{trans('lang.user_plural')}}</p>
                    </a>
                </li>
            @endcan
            @can('permissions.index')
                <li class="nav-item has-treeview {{ Request::is('settings/permissions*') || Request::is('settings/roles*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('settings/permissions*') || Request::is('settings/roles*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-user-secret"></i>@endif
                        <p>
                            {{trans('lang.permission_menu')}}
                            <i class="right fa fa-angle-left"></i>
                        </p></a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('settings/permissions') ? 'active' : '' }}" href="{!! url('settings/permissions') !!}">
                                @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                <p>{{trans('lang.permission_table')}}</p>
                            </a>
                        </li>
                        @can('permissions.create')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings/permissions/create') ? 'active' : '' }}" href="{!! url('settings/permissions/create') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.permission_create')}}</p>
                                </a>
                            </li>
                        @endcan
                        @can('roles.index')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings/roles') ? 'active' : '' }}" href="{!! url('settings/roles') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.role_table')}}</p>
                                </a>
                            </li>
                        @endcan
                        @can('roles.create')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings/roles/create') ? 'active' : '' }}" href="{!! url('settings/roles/create') !!}">
                                    @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                                    <p>{{trans('lang.role_create')}}</p>
                                </a>
                            </li>
                        @endcan
                    </ul>

                </li>
            @endcan
            @can('notifications.index')
                <li class="nav-item">
                    <a href="{!! url('settings/app/notifications') !!}" class="nav-link {{  Request::is('settings/app/notifications*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-bell"></i> @endif
                        <p>{{trans('lang.app_setting_notifications')}}</p>
                    </a>
                </li>
            @endcan
            @can('mails.index')
                <li class="nav-item">
                    <a href="{!! url('settings/mail/smtp') !!}" class="nav-link {{ Request::is('settings/mail*') ? 'active' : '' }}">
                        @if($icons)<i class="nav-icon fa fa-envelope"></i> @endif
                        <p>{{trans('lang.app_setting_mail')}}</p>
                    </a>
                </li>
            @endcan

        </ul>
    </li>
@endcan

