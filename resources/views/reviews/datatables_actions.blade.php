<div class='btn-group btn-group-sm'>
  @can('reviews.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('reviews.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('reviews.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.category_edit')}}" href="{{ route('reviews.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('reviews.destroy')
    {!! Form::open(['route' => ['reviews.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
