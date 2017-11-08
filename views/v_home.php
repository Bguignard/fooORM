<p>Welcome to fooORM, please set the information of the database you want to </p>
<form>
    <div class="form-group">
        <label for="databaseName">Database Name</label>
        <input type="text" class="form-control" id="databaseName" aria-describedby="dbNameHelp" placeholder="Database name">
        <small id="dbNameHelp" class="form-text text-muted">Type here the database name.</small>
    </div>
    <div class="form-group">
        <label for="databasePassword">Password</label>
        <input type="password" class="form-control" id="databasePassword" aria-describedby="dbPasswordHelp" placeholder="Password">
        <small id="dbPasswordHelp" class="form-text text-muted">Type here the database password.</small>
    </div>
    <div class="form-check">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input">
            Check me out
        </label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>