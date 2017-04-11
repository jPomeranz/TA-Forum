<form role="form" class="form-horizontal" id="searchForm" action="index.php" method="post" accept-charset="UTF-8">                
    <div class="form-group">
        <h2>Please enter all information about the TA you're searching for:</h2>
        <label for="name" class="col-sm-2 control-label">TA Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" placeholder="TA Name"/>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Course Title</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="title" name="title" placeholder="Course Title"/>
        </div>
    </div>
    <div class="form-group">
        <label for="dept" class="col-sm-2 control-label">Course Dept</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="dept" name="dept" placeholder="Course Department Abbreviation"/>
        </div>
    </div>
    <div class="form-group">
        <label for="mnemonic" class="col-sm-2 control-label">Course Number</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="mnemonic" name="mnemonic" placeholder="Course Number"/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
            <input type="submit" class="btn btn-primary btn-sm" name="searchSubmitted" value="Search"></input>
        </div>
    </div>
</form>