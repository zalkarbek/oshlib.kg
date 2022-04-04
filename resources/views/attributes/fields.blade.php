<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Title Field -->
    <div class="form-group row ">
      {!! Form::label('title', trans("lang.attribute_title"), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('title', null,  ['class' => 'form-control','placeholder'=>  trans("lang.attribute_title_placeholder")]) !!}
        <div class="form-text text-muted">
          {{ trans("lang.attribute_title_help") }}
        </div>
      </div>
    </div>

    <!-- Key Field -->
    <div class="form-group row ">
        {!! Form::label('key', trans("lang.attribute_key"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('key', null,  ['class' => 'form-control','placeholder'=>  trans("lang.attribute_key_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.attribute_key_help") }}
            </div>
        </div>
    </div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('value', trans("lang.category_description"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('value', null, ['class' => 'form-control','placeholder'=>
             trans("lang.attribute_value_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.attribute_value_help") }}</div>
        </div>
    </div>

    <!-- Key Field -->
    <div class="form-group row ">
        {!! Form::label('comment', trans("lang.comment"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('comment', null,  ['class' => 'form-control','placeholder'=>  trans("lang.comment_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.comment_help") }}
            </div>
        </div>
    </div>

</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.category')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
