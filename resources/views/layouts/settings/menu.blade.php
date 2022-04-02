<div class="card {{ Request::is('console/users*') || Request::is('console/settings/permissions*') || Request::is('console/settings/roles*') ? '' : 'collapsed-card' }}">
    <div class="card-header">
        <h3 class="card-title">{{trans('lang.permission_menu')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa {{ Request::is('console/settings/users*') || Request::is('console/settings/permissions*') || Request::is('console/settings/roles*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{!! url('console/permissions/index') !!}" class="nav-link {{  Request::is('console/settings/permissions*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.permission_plural')}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{!! url('console/roles/index') !!}" class="nav-link {{  Request::is('console/settings/roles*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.role_plural')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/users/index') !!}" class="nav-link {{  Request::is('console/users*') ? 'selected' : '' }}">
                    <i class="fa fa-users"></i> {{trans('lang.user_plural')}}
                </a>
            </li>

        </ul>
    </div>
</div>

<div class="card {{
             Request::is('console/settings/app/*') ||
             Request::is('console/notificationTypes*') ||
             Request::is('console/settings/mail*') ||
             Request::is('console/settings/translation*') ||
             Request::is('console/settings/payment*') ||
             Request::is('console/settings/currencies*') ||
             Request::is('console/settings/customFields*')
 ? '' : 'collapsed-card' }}">
    <div class="card-header">
        <h3 class="card-title">{{trans('lang.app_setting_globals')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa {{
             Request::is('console/settings/app/*') ||
             Request::is('console/settings/mail*') ||
             Request::is('console/settings/translation*') ||
             Request::is('console/settings/payment*') ||
             Request::is('console/settings/currencies*') ||
             Request::is('console/settings/customFields*')
             ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{!! url('console/settings/app/globals') !!}" class="nav-link {{  Request::is('console/settings/app/globals*') ? 'selected' : '' }}">
                    <i class="fa fa-cog"></i> {{trans('lang.app_setting_globals')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/app/localisation') !!}" class="nav-link {{  Request::is('console/settings/app/localisation*') ? 'selected' : '' }}">
                    <i class="fa fa-language"></i> {{trans('lang.app_setting_localisation')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/app/social') !!}" class="nav-link {{  Request::is('console/settings/app/social*') ? 'selected' : '' }}">
                    <i class="fa fa-globe"></i> {{trans('lang.app_setting_social')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/payment/payment') !!}" class="nav-link {{  Request::is('console/settings/payment*') ? 'selected' : '' }}">
                    <i class="fa fa-credit-card"></i> {{trans('lang.app_setting_payment')}}
                </a>
            </li>

            @can('currencies.index')
                <li class="nav-item">
                    <a href="" class="nav-link {{ Request::is('console/settings/currencies*') ? 'selected' : '' }}" ><i class="nav-icon fa fa-dollar ml-1"></i> {{trans('lang.currency_plural')}}</a>
                </li>
            @endcan

            <li class="nav-item">
                <a href="{!! url('console/settings/app/notifications') !!}" class="nav-link {{  Request::is('console/settings/app/notifications*') || Request::is('notificationTypes*') ? 'selected' : '' }}">
                    <i class="fa fa-bell"></i> {{trans('lang.app_setting_notifications')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/mail/smtp') !!}" class="nav-link {{ Request::is('console/settings/mail*') ? 'selected' : '' }}">
                    <i class="fa fa-envelope"></i> {{trans('lang.app_setting_mail')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/translation/en') !!}" class="nav-link {{ Request::is('console/settings/translation*') ? 'selected' : '' }}">
                    <i class="fa fa-language"></i> {{trans('lang.app_setting_translation')}}
                </a>
            </li>

        </ul>
    </div>
</div>


<div class="card {{ Request::is('console/settings/mobile*') ? '' : 'collapsed-card' }}">
    <div class="card-header">
        <h3 class="card-title">{{trans('lang.mobile_menu')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa {{ Request::is('console/settings/mobile*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{!! url('console/settings/mobile/globals') !!}" class="nav-link {{  Request::is('console/settings/mobile/globals*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.mobile_globals')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/mobile/colors') !!}" class="nav-link {{  Request::is('console/settings/mobile/colors*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.mobile_colors')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('console/settings/mobile/home') !!}" class="nav-link {{  Request::is('console/settings/mobile/home*') ? 'selected' : '' }}">
                    <i class="fa fa-home"></i> {{trans('lang.mobile_home')}}
                </a>
            </li>

        </ul>
    </div>
</div>
