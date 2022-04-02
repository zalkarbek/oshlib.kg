<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.currency_name"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.currency_name_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.currency_name_help") }}
    </div>
  </div>
</div>

<!-- Symbol Field -->
<div class="form-group row ">
  {!! Form::label('symbol', trans("lang.currency_symbol"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('symbol', null,  ['class' => 'form-control','placeholder'=>  trans("lang.currency_symbol_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.currency_symbol_help") }}
    </div>
  </div>
</div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.currency')}}</button>
  <a href="{!! route('currencies.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
