$(document).ready(function () {
    $("form").on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();
        var formData = $(this).serialize();
        $.ajax({
            method: "POST",
            url: "controllers/ajax/a_connectDb.php",
            data: formData
        })
        //callback
            .done(function(data) {
                $("#test").html(data);
            });

    });
});