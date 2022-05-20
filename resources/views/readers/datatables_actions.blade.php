<div class='btn-group btn-group-sm'>

  @can('readers.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.reader_edit')}}" href="{{ route('readers.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('readers.destroy')
    {!! Form::open(['route' => ['readers.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
