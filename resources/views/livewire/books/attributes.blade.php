<div class="col-12">
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <div class="col-12 d-flex justify-content-between pb-3">
            <h5 class="">{!! trans('lang.attribute_plural') !!}</h5>
            <a class="btn btn-light text-right" wire:click="addAttribute"><i class="fa fa-plus"></i> {{trans('lang.add')}}</a>
        </div>
        @foreach($bookAttributes as $bookAttribute)
            <div class="col-6 row">
                <div class="col-12 pb-4">
                    {!! Form::select('attributes[]', $attributes, $bookAttribute['id'], [
                        'wire:change' => '$emit("attributeChanged", '.$loop->index.', $event.target.value)',
                        'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-6 row">
                <div class="col-12 pb-4">
                    {!! Form::text('attributes_values[]', $bookAttribute['value'], [
                        'wire:change' => '$emit("attributeValueChanged", '.$loop->index.', $event.target.value)',
                        'class' => 'form-control','placeholder'=> '']) !!}
                </div>
            </div>
        @endforeach
    </div>
</div>
