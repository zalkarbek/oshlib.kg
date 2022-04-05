<div style="max-width: 100%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
      {!! Form::label('title', 'Загаловок', ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('title', null,  ['class' => 'form-control','placeholder'=>  'Введите загаловок']) !!}
      </div>
    </div>

  <!-- Text Field -->
  <div class="form-group row ">
    {!! Form::label('content', 'Контент', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
      {!! Form::textarea('content', null, ['class' => 'form-control','placeholder'=> '']) !!}
    </div>
  </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.article')}}</button>
  <a href="{!! route('articles.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
