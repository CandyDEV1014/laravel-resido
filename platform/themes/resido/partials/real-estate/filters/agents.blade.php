<select id="agent" data-placeholder="{{ __('Agents') }}" name="max_price" class="form-control">
    <option value="">&nbsp;</option>
    @foreach (get_agents() as $agent)
        <option value="{{$agent->account_id}}">{{$agent->first_name}} {{$agent->last_name}}</option>
    @endforeach
</select>



