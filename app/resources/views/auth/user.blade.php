<div class="container">
    <div class="row">
        <div class="col">
            <h2 class="mx-auto text-center">Welcome
                {{ $name }}!
            </h2>
            <form class="form-horizontal" method="POST" action="/logout">
                {{ csrf_field() }}
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" value="Logout">Logout</button>
                </div>
            </form>
        </div>
    </div>
</div>