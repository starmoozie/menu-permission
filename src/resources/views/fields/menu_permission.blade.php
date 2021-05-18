<!-- checklist -->
@php
    $model = new $field['model'];
    $key_attribute = $model->getKeyName();
    $identifiable_attribute = $field['attribute'];

    // calculate the checklist options
    if (!isset($field['options'])) {
        $menu = \Starmoozie\MenuPermission\app\Models\Menu::orderBy('lft')->get();
        $menu = $menu->load(['permission']);
        $field['options'] = $menu->sortBy('name')->pluck($identifiable_attribute, $key_attribute)->toArray();
    } else {
        $field['options'] = call_user_func($field['options'], $field['model']::query());
    }

    if(!empty($field["value"]) && !is_array($field['value'])) {
        $field['value'] = $field['value']->pluck('id', 'id')->values()->toArray();
    }
    elseif(!empty($field["value"]) && is_array($field['value'])) {
        $field['value'] = array_map('intval', $field['value']);
    }

    // define the init-function on the wrapper
    $field['wrapper']['data-init-function'] =  $field['wrapper']['data-init-function'] ?? 'bpFieldInitChecklist';
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <input type="hidden" value="@if(isset($field['value'])) @json($field['value']) @endif" name="{{ $field['name'] }}">

    <div class="row">
        @foreach ($menu as $key => $option)
            <div class="col-md-12">
                <div class="checkbox">
                    <i class="nav-icon la la-list"></i> {{ $option->name }}
                </div>
                @foreach($option->permission as $child)
                    <div class="col-sm-4">
                        <div class="checkbox">
                            <input type="checkbox" value="{{ $child->pivot->id }}" class="shadow-sm"> {{ $child->name }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp
    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script>
            function bpFieldInitChecklist(element) {
                var hidden_input = element.find('input[type=hidden]');

                var selected_options = JSON.parse(hidden_input.val() || '[]');

                var checkboxes = element.find('input[type=checkbox]');
                var container = element.find('.row');

                // set the default checked/unchecked states on checklist options
                checkboxes.each(function(key, option) {
                    var id = parseInt($(this).val());

                    if (selected_options.includes(id)) {
                        $(this).prop('checked', 'checked');
                    } else {
                        $(this).prop('checked', false);
                    }
                });

                // when a checkbox is clicked
                // set the correct value on the hidden input
                checkboxes.click(function() {
                    var newValue = [];

                    checkboxes.each(function() {
                        if ($(this).is(':checked')) {
                        var id = parseInt($(this).val());
                        newValue.push(id);
                        }
                    });

                    hidden_input.val(JSON.stringify(newValue));

                });
            }
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}