<div class='btn-group btn-group-sm'>
    @can('articles.categories.show')
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('articles.categories.show', $id) }}" class='btn btn-link'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan

    @can('articles.categories.edit')
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.article_category_edit')}}" href="{{ route('articles.categories.edit', $id) }}" class='btn btn-link'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan

    @can('articles.categories.destroy')
        {!! Form::open(['route' => ['articles.categories.destroy', $id], 'method' => 'delete']) !!}
        {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-link text-danger',
        'onclick' => "return confirm('Are you sure?')"
        ]) !!}
        {!! Form::close() !!}
    @endcan
</div>
