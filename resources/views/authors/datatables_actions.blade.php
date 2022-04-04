<div class='btn-group btn-group-sm'>
  @can('authors.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('authors.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('authors.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.author_edit')}}" href="{{ route('authors.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('authors.destroy')
    {!! Form::open(['route' => ['authors.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
