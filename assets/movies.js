jQuery( function( $ )  {
    $(document).ready( function(){
        $(document).on("submit", ".films-filter-form", function(e) {
            e.preventDefault();
            var data = $(this).serialize();
            //console.log(data);
            $.ajax({
                url:MOVIES.movies_url,
                type:'POST',
                data:data,
                success:function(response) {
                    $('.wj-films').html(response).hide().fadeIn(1000);
					console.log(response);
                }
            })
        });
    });
});