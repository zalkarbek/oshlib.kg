<div style="max-width: 100%;padding: 0 4px;" class="column">
    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('imgInp', trans("lang.category_image"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div onclick="uploadFile()" id="imgCont" style="width: 100%; display: flex; align-items: center; text-align: center; background-size: contain; background-position: center; background-repeat: no-repeat;" class="dropzone image">
                <p style="width: 100%;" id="uploadText"><i class="fa fa-cloud-upload"></i> Загрузить</p>
            </div>
            <input type="file" id="imgInp" class="d-none" name="image">
        </div>
    </div>
    @prepend('scripts')
        <script type="text/javascript">
            imgInp.onchange = evt => {
                const [file] = imgInp.files
                if (file) {
                    const url = URL.createObjectURL(file)
                    console.log(url)
                    imgCont.style.backgroundImage = 'url("' + url + '")'
                    uploadText.style.display = 'none'
                }
            }

            function uploadFile() {
                imgInp.click();
            }
        </script>
    @endprepend
    <!-- Name Field -->
    <div class="form-group row ">
      {!! Form::label('title', 'Загаловок', ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        {!! Form::text('title', null,  ['class' => 'form-control','placeholder'=>  'Введите загаловок']) !!}
      </div>
    </div>

    <!-- Region Id Field -->
    <div class="form-group row ">
        {!! Form::label('category_id', trans("lang.category"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('category_id', $categories, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('short_description', 'Краткое описание', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('short_description', null,  ['class' => 'form-control','placeholder'=> '']) !!}
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
