<!DOCTYPE html>
<html>
<head>
    <title>d</title>

</head>
<body>
<button class="print1">Print</button>
<section>
    <div class="content-print1">
        <h2>This Will Be Printed</h2>
        <p>Stare at the wall, play with food and get confused by dust eat and than sleep on your face. Asdflkjaertvlkjasntvkjn (sits on keyboard) play time scamper human give me attention meow. Vommit food and eat it again meowwww so i am the best but sun
            bathe, yet white cat sleeps on a black shirt. Kick up litter flop over, or give attitude. Make meme, make cute face stare at the </p>
    </div>
</section>

<script type="text/javascript">
    $('.print').on('click', function() { // select print button with class "print," then on click run callback function
        $.print(".content-print1"); // inside callback function the section with class "content" will be printed
    });

</script>
<script type="text/javascript" src="{{asset('public/js/jquery.print.js')}}"></script>

</body>
</html>
