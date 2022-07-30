<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
      {!! Form::label('book_name', 'Книга', ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('book_name', null,  ['class' => 'form-control','placeholder' => 'Введите название книги']) !!}
      </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('author_name', 'Автор', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('author_name', null,  ['class' => 'form-control','placeholder' => 'Введите название книги']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('inventory_number', 'Инвентаризационный номер', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('inventory_number', null,  ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('department', 'Отдел', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('department', null,  ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('bail_received', 'Получен залог паспорта', ['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox">
            {!! Form::checkbox('bail_received', '1', null, ['class' => 'form-control', 'style' => 'height: 18px; width: 18px; margin: 5px 0 0 8px;']) !!}
        </div>
    </div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('issue_date', 'Дата получения', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::date('issue_date', $issueDate ?? null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('return_date', 'Дата возврата', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::date('return_date', $returnDate ?? null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('note', 'Примечание', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('note', null, ['class' => 'form-control','placeholder'=> 'Примечание'  ]) !!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('reader_id', 'Читатель', ['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox col-9">
            {!! Form::select('reader_id', $users, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.rented-book')}}</button>
  <a href="{!! route('rented-books.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
