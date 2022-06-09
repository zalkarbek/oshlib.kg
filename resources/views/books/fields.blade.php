<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('book_name', trans("lang.book_name"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('book_name', isset($book) ? $book->name : null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
        </div>
    </div>

    <!-- Author Id Field -->
    {{--
    <div class="form-group row ">
        {!! Form::label('author_id', trans('lang.author'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('author_id', $authors, null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>
    --}}

    <!-- Author Id Field -->
    <div class="form-group row ">
        {!! Form::label('authors[]', trans('lang.author'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('authors[]', $authors, $bookAuthors, ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
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
            {{--
            <div class="form-text text-muted">
                <div class="btn btn-white" onclick="showDialog()"><i class="fa fa-plus"></i></div>
            </div>
            --}}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('release_date', 'Дата публикации', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::date('release_date', $releaseDate, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('writing_date', 'Дата написания', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::date('writing_date', $writingDate, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('book_file', trans('lang.book'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::file('book_file', ['class' => 'select2 form-control', 'accept' => 'application/pdf']) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('page_count', trans('lang.book_page_count'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('page_count', null, ['class' => 'select2 form-control', 'placeholder'=>  trans("lang.book_page_count_placeholder")]) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('tags[]', trans('lang.tag_plural'),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('tags[]', $tags, $bookTags, ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
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
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=> 'Описание' ]) !!}
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('has_variants', ' Есть варианты', ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('has_variants', ['all' => 'Оба', 'electronic' => 'Электронный', 'paper' => 'Бумажный'], null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('available_for_rent', 'Доступен для аренды', ['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox">
            {!! Form::checkbox('available_for_rent', null, null, ['style' => 'height: 18px; width: 18px; margin: 5px 0 0 8px;']) !!}
        </div>
    </div>
</div>

@if(!empty($attributes))
    @if(isset($book))
        <livewire:books.attributes :book="$book" />
    @else
        <livewire:books.attributes />
    @endif
@endif

<x-add-dialog id="categoryDialog" title="title">
    <x-slot name="title">
        Добавление категории
    </x-slot>
    <x-slot name="content">
        @include('categories.forms')
    </x-slot>
</x-add-dialog>

<script type="text/javascript">
    function showDialog() {
        window.categoryDialog.show();
    }
</script>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.book')}}</button>
  <a href="{!! route('books.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
