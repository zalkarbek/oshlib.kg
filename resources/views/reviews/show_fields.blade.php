<!-- Id Field -->
<div class="form-group row col-6">
  {!! Form::label('id', 'Id:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $review->id !!}</p>
  </div>
</div>

<!-- Name Field -->
<div class="form-group row col-6">
  {!! Form::label('name', trans('lang.user'), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $review->user->name !!}</p>
  </div>
</div>

<!-- Description Field -->
<div class="form-group row col-6">
  {!! Form::label('book', trans('lang.book'), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $review->book->name !!}</p>
  </div>
</div>

<!-- Image Field -->
<div class="form-group row col-6">
  {!! Form::label('rating', trans('lang.review_rating'), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $review->rating !!}</p>
  </div>
</div>

<!-- Created At Field -->
<div class="form-group row col-6">
  {!! Form::label('created_at', trans('lang.created_at'), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $review->created_at !!}</p>
  </div>
</div>

<!-- Updated At Field -->
<div class="form-group row col-6">
  {!! Form::label('updated_at', trans('lang.updated_at'), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $review->updated_at !!}</p>
  </div>
</div>

<div class="col-12 d-flex justify-content-center">
{!! Form::label('text', trans('lang.review_text')) !!}
</div>

<!-- Image Field -->
<div class="form-group row col-12">
    <div class="col-9">
        <p>{!! $review->text !!}</p>
    </div>
</div>
