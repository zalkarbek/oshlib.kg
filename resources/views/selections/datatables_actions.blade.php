<div class='btn-group btn-group-sm'>
  @can('selections.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('selections.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('selections.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.selection_edit')}}" href="{{ route('selections.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('selections.destroy')
    {!! Form::open(['route' => ['selections.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
