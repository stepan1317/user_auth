<form class="form-horizontal" method="POST" action="/login">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="Login">Login: </label>
        <br/>
        <input type="text" class="form-control" id="login" placeholder="Login" name="login" required>
    </div>
    <div class="form-group">
        <label for="Pass">Pass: </label>
        <br/>
        <input type="text" class="form-control" id="pass" placeholder="Pass" name="pass" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" value="Send">Send</button>
    </div>
</form>
