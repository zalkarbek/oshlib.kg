<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
      {!! Form::label('name', trans("lang.tag_name"), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
        <div class="form-text text-muted">
          {{ trans("lang.category_name_help") }}
        </div>
      </div>
    </div>

    <!-- Parent Id Field -->
    <div class="form-group row ">
        {!! Form::label('type', trans('lang.tag_type'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('type', $types, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('comment', trans("lang.comment"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('comment', null,  ['class' => 'form-control','placeholder'=>  trans("lang.comment_placeholder")]) !!}
        </div>
    </div>

</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Image Field -->
    <div class="form-group row">
      {!! Form::label('imgInp', trans("lang.category_image"), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        <div onclick="uploadFile()" id="imgCont" style="width: 100%; display: flex; align-items: center; text-align: center; background-size: contain; background-position: center; background-repeat: no-repeat;" class="dropzone image">
            <p style="width: 100%;" id="uploadText"><i class="fa fa-cloud-upload"></i> Загрузить</p>
        </div>
        <input type="file" id="imgInp" class="d-none" name="file">
        <div class="form-text text-muted w-50">
          {{ trans("lang.category_image_help") }}
        </div>
      </div>
    </div>
    @prepend('scripts')
        <script type="text/javascript">
            imgInp.onchange = evt => {
                const [file] = imgInp.files
                if (file) {
                    const url = URL.createObjectURL(file)
                    imgCont.style.backgroundImage = 'url("' + url + '")'
                    uploadText.style.display = 'none'
                }
            }

            function uploadFile() {
                imgInp.click();
            }
        </script>
    @endprepend

    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('description', trans("lang.tag_description"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.tag_description_placeholder")  ]) !!}
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.tag')}}</button>
  <a href="{!! route('tags.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
