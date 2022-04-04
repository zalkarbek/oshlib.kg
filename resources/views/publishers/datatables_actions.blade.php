<div class='btn-group btn-group-sm'>
  @can('publishers.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('publishers.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('publishers.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.publisher_edit')}}" href="{{ route('publishers.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('publishers.destroy')
    {!! Form::open(['route' => ['publishers.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
