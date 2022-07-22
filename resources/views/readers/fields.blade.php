<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('sur_name', 'Фамиля', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('sur_name', null,  ['class' => 'form-control','placeholder' => 'Введите фамилю']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', 'Имя', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder' => 'Введите имя']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('patronymic', 'Отчество', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('patronymic', null,  ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('birth_date', 'Дата рождения', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::date('birth_date', $birthDate ?? null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('nationality', 'Национальность', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('nationality', null,  ['class' => 'form-control','placeholder' => 'Введите национальность']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('work_place', 'Место работы', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('work_place', null,  ['class' => 'form-control','placeholder' => 'место работы']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('work_position', 'Должность', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('work_position', null,  ['class' => 'form-control','placeholder' => 'Введите должность']) !!}
        </div>
    </div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('home_address', 'Адрес', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('home_address', null,  ['class' => 'form-control','placeholder' => 'Введите адрес']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('phone', 'Телефон', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Введите телефон']) !!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('passport_id', 'Номер паспорта', ['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox">
            {!! Form::text('passport_id', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('note', 'Примечание', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('note', null, ['class' => 'form-control','placeholder'=> 'Примечание'  ]) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('user_id', 'Пользователи', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('user_id', $users, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.reader')}}</button>
  <a href="{!! route('rented-books.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
