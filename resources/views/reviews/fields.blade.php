<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Parent Id Field -->
    <div class="form-group row ">
        {!! Form::label('book_id', trans('lang.book'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('book_id', $books, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Parent Id Field -->
    <div class="form-group row ">
        {!! Form::label('user_id', trans('lang.user'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('user_id', $users, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('rating', trans("lang.review_rating"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('rating', null,  ['class' => 'form-control','min' => '0', 'max' => '5']) !!}
        </div>
    </div>

</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('text', trans("lang.review_text"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('text', null, ['class' => 'form-control','placeholder'=>
             trans("lang.review_text")  ]) !!}
        </div>
    </div>

</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.review')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
