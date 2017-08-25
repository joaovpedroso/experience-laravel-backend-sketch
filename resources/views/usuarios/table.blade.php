<table class="table table-responsive" id="usuarios-table">
    <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Password</th>
        <th>Remember Token</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($usuarios as $usuarios)
        <tr>
            <td>{!! $usuarios->name !!}</td>
            <td>{!! $usuarios->email !!}</td>
            <td>{!! $usuarios->password !!}</td>
            <td>{!! $usuarios->remember_token !!}</td>
            <td>
                {!! Form::open(['route' => ['usuarios.destroy', $usuarios->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('usuarios.show', [$usuarios->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('usuarios.edit', [$usuarios->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>