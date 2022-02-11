<div class="form-group  {{ $errors->has($name) ? 'has-error' : '' }} {!! $wrapper !!}">
    <label for="{{$name}}" class="control-label">{{$label}}</label>
    <input class="form-control" name="{{$name}}" value="{{$value}}" placeholder="{{$placeholder}}" {!! $props !!}>
    {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
</div>

