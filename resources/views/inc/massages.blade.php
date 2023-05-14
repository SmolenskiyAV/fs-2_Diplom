@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>&#9785; {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        &#9786;&#128077; {{session('success')}}
    </div>
@endif

@if(session('baddata'))
    <div class="alert alert-danger">
    &#9785; {{session('baddata')}}
    </div>
@endif
