<!DOCTYPE html>
<html>
    <head>
      <title>Pusher Test</title>
      <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" defer></script>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <script>
    
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;
    
        var pusher = new Pusher('302b7c164acd6c979a34', {
          cluster: 'ap1',
          forceTLS: true
        });
    
        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
          alert(JSON.stringify(data));
        });
      </script>
    </head>
    <body>
      <h1>Pusher Test</h1>
      <p>
        Try publishing an event to channel <code>my-channel</code>
        with event name <code>my-event</code>.
      </p>
    </body>
</html>