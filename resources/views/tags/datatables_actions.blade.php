<div class='btn-group btn-group-sm'>
  @can('tags.show')
      <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('categories.show', $id) }}" class='btn btn-link'>
        <i class="fa fa-eye"></i>
      </a>
  @endcan

  @can('tags.edit')
      <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.tag_edit')}}" href="{{ route('categories.edit', $id) }}" class='btn btn-link'>
        <i class="fa fa-edit"></i>
      </a>
  @endcan

  @can('tags.destroy')
    {!! Form::open(['route' => ['tags.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
