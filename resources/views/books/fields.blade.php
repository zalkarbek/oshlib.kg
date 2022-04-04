<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', trans("lang.book_name"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('name', null,  ['wire:model' => 'name', 'class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.category_name_help") }}
            </div>
        </div>
    </div>

    <!-- Author Id Field -->
    <div class="form-group row ">
        {!! Form::label('author_id', trans('lang.author'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('author_id', $authors, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Publisher Id Field -->
    <div class="form-group row ">
        {!! Form::label('publisher_id', trans('lang.publisher'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('publisher_id', $publishers, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('category_id', trans('lang.category'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('category_id', $categories, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('release_date', trans('lang.book_release_date'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::date('release_date', null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('file', trans('lang.book'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::file('file', ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('attributes[]', trans('lang.attribute_plural'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('attributes[]', $attributes, [], ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
        </div>
    </div>

</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Image Field -->
    <div class="form-group row">
      {!! Form::label('imgInp', trans("lang.book_image"), ['class' => 'col-3 control-label text-right']) !!}
      <div class="col-9">
        <div onclick="uploadFile()" id="imgCont" style="width: 100%; display: flex; align-items: center; text-align: center; background-size: contain; background-position: center; background-repeat: no-repeat;" class="dropzone image">
            <p style="width: 100%;" id="uploadText"><i class="fa fa-cloud-upload"></i> Загрузить</p>
        </div>
        <input type="file" id="imgInp" class="d-none" name="image">
        <div class="form-text text-muted w-50">
          {{ trans("lang.book_image_help") }}
        </div>
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

    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('description', trans("lang.book_description"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.book_description_placeholder")  ]) !!}
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.book')}}</button>
  <a href="{!! route('books.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
