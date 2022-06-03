<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', trans("lang.category_name"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.category_name_help") }}
            </div>
        </div>
    </div>

    <!-- Parent Id Field -->
    <div class="form-group row ">
        {!! Form::label('parent_id', 'Родитель',['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('parent_id', $categories, null, ['class' => 'select2 form-control']) !!}
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
</div>
