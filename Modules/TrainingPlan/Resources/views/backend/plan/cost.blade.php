<div class="row">
    <div class="col-12">
        <table class="tDefault table table-hover">
            <thead>
                <tr>
                    <th>Tên chi phí dào tạo</th>
                    <th>Loại chi phí</th>
                    <th style="width: 12%;">Chi phí (VNĐ)</th>
                    <th>Loại hình đào tạo</th>
                    <th>Diễn giải</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($get_training_type_costs as $get_training_type_cost)
                    @if (!empty($get_type_model_costs))
                        @foreach ($get_type_model_costs as $item)
                            @if ($item->id == $get_training_type_cost->id)
                                <tr>
                                    <td>
                                        {{ $get_training_type_cost->name }}
                                    </td>
                                    <td>
                                        {{ $get_training_type_cost->type_cost_name }}
                                    </td>
                                    <td>
                                        <input type="hidden" name="id_type_costs_plan[]" value="{{ $get_training_type_cost->type_cost_id }}">
                                        <input type="hidden" name="id_costs_plan[]" value="{{ $item->id }}">
                                        <input name="costs_plan_detail[]" id="cost_plan_detail_{{$item->id}}" type="text" class="form-control costs_plan_detail" value="{{ $item->set_cost ? number_format($item->set_cost, 0) : 0 }}">
                                    </td>
                                    <td>
                                        <select name="training_form_{{$item->id}}[]" id="" class="form-control select2"  multiple>
                                            @foreach ($training_forms as $training_form)
                                                <option value="{{ $training_form->id }}" {{ !empty($item->training_form_id) && in_array($training_form->id, $item->training_form_id) ? 'selected' : '' }}>{{ $training_form->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="explan[]" value="{{ isset($item->explan) ? $item->explan : '' }}">
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td>
                                {{ $get_training_type_cost->name }}
                            </td>
                            <td>
                                {{ $get_training_type_cost->type_cost_name }}
                            </td>
                            <td>
                                <input type="hidden" name="id_type_costs_plan[]" value="{{ $get_training_type_cost->type_cost_id }}">
                                <input type="hidden" name="id_costs_plan[]" value="{{ $get_training_type_cost->id }}">
                                <input name="costs_plan_detail[]" id="cost_plan_detail_{{$get_training_type_cost->id}}" type="text" class="form-control costs_plan_detail" value="">
                            </td>
                            <td>
                                <select name="training_form_{{$get_training_type_cost->id}}[]" id="" class="form-control select2" multiple>
                                    @foreach ($training_forms as $training_form)
                                        <option value="{{ $training_form->id }}">{{ $training_form->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="explan[]">
                            </td>
                        </tr>
                    @endif
                @endforeach
                @if ( !empty($get_type_cost_id) && !empty(array_diff($array_type_cost, $get_type_cost_id)) && !empty($type_costs_new) )
                    @foreach ($type_costs_new as $type_cost_new)
                    <tr>
                        <td>
                            {{ $type_cost_new->name }}
                        </td>
                        <td>
                            {{ $type_cost_new->type_cost_name }}
                        </td>
                        <td>
                            <input type="hidden" name="id_type_costs_plan[]" value="{{ $type_cost_new->type_cost_id }}">
                            <input type="hidden" name="id_costs_plan[]" value="{{ $type_cost_new->id }}">
                            <input name="costs_plan_detail[]" id="cost_plan_detail_{{$type_cost_new->id}}" type="text" class="form-control costs_plan_detail" value="">
                        </td>
                        <td>
                            <select name="training_form_{{$type_cost_new->id}}[]" id="" class="form-control select2" multiple>
                                @foreach ($training_forms as $training_form)
                                    <option value="{{ $training_form->id }}">{{ $training_form->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="explan[]">
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<script>
    (function($, undefined) {
	    $(function() {
	        var $form = $( "#form" );
	        var $input = $form.find( "input[name='costs_plan_detail[]']" );
	        $input.on( "keyup", function( event ) {
	            var $this = $( this );
	            // Get the value.
	            var input = $this.val();
	            var input = input.replace(/[\D\s\._\-]+/g, "");
	            input = input ? parseInt( input, 10 ) : 0;

	            $this.val( function() {
	                return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
	            } );
	        } );

	    });
	})(jQuery);
</script>