jQuery(document).ready(function ($) {
    $('#msgForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: {
                action: "submitAction",
                content: $(this).serialize(),
            }, // form data
            success: function (data) {
                $('#msgForm')[0].reset();
                $(".msgs").html(data);
                var wtf = $('#msgsBox');
                var height = wtf[0].scrollHeight;
                wtf.scrollTop(height);
            }
        });

    });
    $('#msgFormWidget').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: {
                action: "submitAction",
                content: $(this).serialize(),
            }, // form data
            success: function (data) {
                $('#msgFormWidget')[0].reset();
                $(".msgs").html(data);
                var wtf = $('#msgsBoxWidget');
                var height = wtf[0].scrollHeight;
                wtf.scrollTop(height);
            }
        });

    });
     $('#msgFormpopup').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: {
                action: "submitAction",
                content: $(this).serialize(),
            }, // form data
            success: function (data) {
                $('#msgFormpopup')[0].reset();
                $(".msgs").html(data);
                var wtf = $('#msgsBoxpopup');
                var height = wtf[0].scrollHeight;
                wtf.scrollTop(height);
            }
        });

    });
    
    
    function loadlink() {
        var link = $('.msgForm').attr("action");
        $.ajax({
            type: "POST",
            url: link,
            data: {
                action: "submitAction",
            }, // form data
            success: function (data) {

                $(".msgs").html(data);
            }
        });

    }

    loadlink(); // This will run on page load
    setInterval(function () {
        loadlink() // this will run after every 5 seconds
    }, 5000);
    
    $('.openWindow').click( function(){
       $('.popupWindow').toggle();
    });
    
});

