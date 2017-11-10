<p>Welcome to fooORM, please set the information of the database you want to </p>
<form method="post">
    <div class="form-group">
        <label for="databaseName">Database Name</label>
        <input type="text" class="form-control" id="databaseName" name="databaseName" aria-describedby="dbNameHelp" placeholder="Database name">
        <small id="dbNameHelp" class="form-text text-muted">Type here the database name.</small>
    </div>
    <div class="form-group">
        <label for="databaseUserName">Database User</label>
        <input type="text" class="form-control" id="databaseUserName" name="databaseUserName" aria-describedby="dbUserNameHelp" placeholder="Database user">
        <small id="dbUserNameHelp" class="form-text text-muted">Type here the database user.</small>
    </div>
    <div class="form-group">
        <label for="databasePassword">Password</label>
        <input type="password" class="form-control" id="databasePassword" name="databasePassword" aria-describedby="dbPasswordHelp" placeholder="Password">
        <small id="dbPasswordHelp" class="form-text text-muted">Type here the database password.</small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<div id="test">

</div>