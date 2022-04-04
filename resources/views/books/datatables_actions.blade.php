<div class='btn-group btn-group-sm'>
  @can('books.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('books.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('books.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.book_edit')}}" href="{{ route('books.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('books.destroy')
    {!! Form::open(['route' => ['books.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Вы уверены?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
