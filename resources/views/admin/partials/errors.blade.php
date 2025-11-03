@if (session('message') || $errors->has('toast_error') || $errors->any())
    <div id="toast-data"
         data-toast-message="{{ session('message')
            ?? ($errors->has('toast_error')
                ? $errors->first('toast_error')
                : implode(' | ', $errors->all())) }}"
         data-toast-error="{{ ($errors->has('toast_error') || $errors->any()) ? 'true' : 'false' }}">
    </div>
@endif
