<div class='btn-group btn-group-sm'>
  @can('rented-books.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.rented-book_edit')}}" href="{{ route('rented-books.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('rented-books.destroy')
    {!! Form::open(['route' => ['rented-books.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
